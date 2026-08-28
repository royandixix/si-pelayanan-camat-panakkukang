<?php

namespace App\Http\Controllers\Masyarakat;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\ServiceApplication;
use App\Models\ServiceQueue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DasborController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $pengguna = Auth::user();

        if (!$pengguna) {
            return redirect()->route('login');
        }

        $role = $pengguna->role instanceof UserRole
            ? $pengguna->role
            : UserRole::tryFrom((string) $pengguna->role);

        if ($role !== UserRole::MASYARAKAT || !$pengguna->is_active) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Akses tidak diizinkan',
                    'text' => 'Akun Anda tidak memiliki akses ke Portal Masyarakat.',
                    'confirmButtonText' => 'Mengerti',
                ]);
        }

        $dasarPermohonan = ServiceApplication::query()
            ->where('user_id', $pengguna->id);

        $totalPermohonan = (clone $dasarPermohonan)->count();

        $menungguVerifikasi = (clone $dasarPermohonan)
            ->whereIn('status', [
                ApplicationStatus::SUBMITTED->value,
                ApplicationStatus::VERIFICATION->value,
            ])
            ->count();

        $sedangDiproses = (clone $dasarPermohonan)
            ->whereIn('status', [
                ApplicationStatus::PROCESSING->value,
                ApplicationStatus::APPROVED->value,
            ])
            ->count();

        $perluPerbaikan = (clone $dasarPermohonan)
            ->where('status', ApplicationStatus::REVISION->value)
            ->count();

        $selesai = (clone $dasarPermohonan)
            ->where('status', ApplicationStatus::COMPLETED->value)
            ->count();

        $permohonanTerbaru = (clone $dasarPermohonan)
            ->with(['service.section'])
            ->withCount('documents')
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $antreanAktif = ServiceQueue::query()
            ->with(['service', 'section', 'application'])
            ->where('user_id', $pengguna->id)
            ->whereDate('queue_date', '>=', today())
            ->whereNotIn('status', [
                'completed',
                'cancelled',
            ])
            ->orderBy('queue_date')
            ->orderBy('sequence')
            ->first();

        $sapaan = match (true) {
            now()->hour < 11 => 'Selamat pagi',
            now()->hour < 15 => 'Selamat siang',
            now()->hour < 18 => 'Selamat sore',
            default => 'Selamat malam',
        };

        return view('masyarakat.dashboard', compact(
            'pengguna',
            'sapaan',
            'totalPermohonan',
            'menungguVerifikasi',
            'sedangDiproses',
            'perluPerbaikan',
            'selesai',
            'permohonanTerbaru',
            'antreanAktif',
        ));
    }
}
<?php

namespace App\Http\Controllers\Masyarakat;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function index(Request $request): View
    {
        $pengguna = $this->penggunaMasyarakat($request);

        $status = $request->string('status')->toString();

        if (!in_array($status, ['semua', 'belum-dibaca', 'sudah-dibaca'], true)) {
            $status = 'semua';
        }

        $query = $pengguna
            ->notifications()
            ->latest();

        if ($status === 'belum-dibaca') {
            $query->whereNull('read_at');
        }

        if ($status === 'sudah-dibaca') {
            $query->whereNotNull('read_at');
        }

        $notifikasi = $query
            ->paginate(10)
            ->withQueryString();

        $jumlahSemua = $pengguna
            ->notifications()
            ->count();

        $jumlahBelumDibaca = $pengguna
            ->unreadNotifications()
            ->count();

        $jumlahSudahDibaca = $pengguna
            ->readNotifications()
            ->count();

        return view(
            'masyarakat.notifikasi.index',
            compact(
                'pengguna',
                'status',
                'notifikasi',
                'jumlahSemua',
                'jumlahBelumDibaca',
                'jumlahSudahDibaca',
            ),
        );
    }

    public function buka(
        Request $request,
        string $notifikasi,
    ): RedirectResponse {
        $pengguna = $this->penggunaMasyarakat($request);

        $item = $pengguna
            ->notifications()
            ->whereKey($notifikasi)
            ->firstOrFail();

        if ($item->unread()) {
            $item->markAsRead();
        }

        $tujuan = data_get($item->data, 'url');

        if (
            !is_string($tujuan)
            || !str_starts_with($tujuan, '/')
            || str_starts_with($tujuan, '//')
        ) {
            return redirect()
                ->route('masyarakat.notifikasi.index');
        }

        return redirect()->to($tujuan);
    }

    public function baca(
        Request $request,
        string $notifikasi,
    ): RedirectResponse {
        $pengguna = $this->penggunaMasyarakat($request);

        $item = $pengguna
            ->notifications()
            ->whereKey($notifikasi)
            ->firstOrFail();

        if ($item->unread()) {
            $item->markAsRead();
        }

        return back()->with('swal', [
            'icon' => 'success',
            'title' => 'Notifikasi dibaca',
            'text' => 'Notifikasi berhasil ditandai sebagai sudah dibaca.',
            'confirmButtonText' => 'Selesai',
        ]);
    }

    public function bacaSemua(Request $request): RedirectResponse
    {
        $pengguna = $this->penggunaMasyarakat($request);

        $jumlah = $pengguna
            ->unreadNotifications()
            ->count();

        if ($jumlah > 0) {
            $pengguna
                ->unreadNotifications()
                ->update([
                    'read_at' => now(),
                ]);
        }

        return back()->with('swal', [
            'icon' => 'success',
            'title' => 'Notifikasi diperbarui',
            'text' => $jumlah > 0
                ? "{$jumlah} notifikasi berhasil ditandai sebagai sudah dibaca."
                : 'Tidak ada notifikasi baru yang perlu ditandai.',
            'confirmButtonText' => 'Selesai',
        ]);
    }

    private function penggunaMasyarakat(Request $request): User
    {
        $pengguna = $request->user();

        $role = $pengguna?->role instanceof UserRole
            ? $pengguna->role
            : UserRole::tryFrom((string) $pengguna?->role);

        abort_unless(
            $pengguna instanceof User
            && $role === UserRole::MASYARAKAT
            && $pengguna->is_active,
            403,
        );

        return $pengguna;
    }
}
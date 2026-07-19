<?php

namespace App\Http\Controllers\Masyarakat;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\ApplicationResult;
use App\Models\ServiceApplication;
use App\Models\User;
use BackedEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HasilPelayananController extends Controller
{
    public function download(
        Request $request,
        ServiceApplication $permohonan,
    ): StreamedResponse {
        $pengguna = $this->penggunaMasyarakat($request);

        abort_unless(
            (int) $permohonan->user_id
            === (int) $pengguna->id,
            404,
        );

        $status = $permohonan->status instanceof BackedEnum
            ? (string) $permohonan->status->value
            : (string) $permohonan->status;

        abort_unless(
            $status === ApplicationStatus::COMPLETED->value,
            404,
        );

        $hasil = $permohonan
            ->result()
            ->whereNotNull('published_at')
            ->firstOrFail();

        $disk = filled($hasil->disk)
            ? $hasil->disk
            : 'local';

        abort_unless(
            Storage::disk($disk)->exists($hasil->path),
            404,
            'Dokumen hasil tidak ditemukan.',
        );

        ApplicationResult::query()
            ->whereKey($hasil->id)
            ->update([
                'download_count' => $hasil->download_count + 1,
                'last_downloaded_at' => now(),
            ]);

        return Storage::disk($disk)->download(
            $hasil->path,
            $hasil->original_name,
            [
                'Content-Type' => $hasil->mime_type
                    ?: 'application/pdf',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store, max-age=0',
            ],
        );
    }

    private function penggunaMasyarakat(
        Request $request,
    ): User {
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
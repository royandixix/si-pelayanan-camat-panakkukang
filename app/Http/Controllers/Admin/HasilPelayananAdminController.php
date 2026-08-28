<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HasilPelayananAdminController extends Controller
{
    public function download(
        ServiceApplication $permohonan,
    ): StreamedResponse {
        $user = Auth::guard('admin')->user();

        abort_unless(
            $user instanceof User
            && $user->is_active,
            403,
        );

        $permohonan->loadMissing([
            'service.section',
            'result',
        ]);

        if (! $user->isSuperAdmin()) {
            abort_unless(
                $user->isAdminSeksi()
                && $user->section_id !== null
                && $permohonan->service?->section_id === $user->section_id,
                403,
            );
        }

        $hasil = $permohonan->result;

        abort_if(
            ! $hasil
            || ! Storage::disk($hasil->disk ?: 'local')
                ->exists($hasil->path),
            404,
            'Dokumen hasil pelayanan tidak ditemukan.',
        );

        $hasil->increment('download_count');

        $hasil->forceFill([
            'last_downloaded_at' => now(),
        ])->save();

        return Storage::disk(
            $hasil->disk ?: 'local',
        )->download(
            $hasil->path,
            $hasil->original_name
                ?: 'hasil-' . $permohonan->registration_number . '.pdf',
            [
                'Content-Type' => $hasil->mime_type
                    ?: 'application/pdf',
            ],
        );
    }
}

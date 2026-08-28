<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationDocument;
use App\Models\ServiceApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenPermohonanAdminController extends Controller
{
    public function show(
        ServiceApplication $permohonan,
        ApplicationDocument $dokumen,
    ) {
        $user = Auth::guard('admin')->user();

        abort_unless(
            $user instanceof User
            && $user->is_active,
            403,
        );

        abort_unless(
            $dokumen->application_id === $permohonan->id,
            404,
        );

        $permohonan->loadMissing('service.section');

        if (! $user->isSuperAdmin()) {
            abort_unless(
                $user->isAdminSeksi()
                && $user->section_id !== null
                && $permohonan->service?->section_id === $user->section_id,
                403,
            );
        }

        $disk = $dokumen->disk ?: 'local';

        abort_unless(
            filled($dokumen->path)
            && Storage::disk($disk)->exists($dokumen->path),
            404,
            'Dokumen tidak ditemukan.',
        );

        return Storage::disk($disk)->response(
            $dokumen->path,
            $dokumen->original_name
                ?: basename($dokumen->path),
            [
                'Content-Type' => $dokumen->mime_type
                    ?: 'application/octet-stream',
            ],
            'inline',
        );
    }
}

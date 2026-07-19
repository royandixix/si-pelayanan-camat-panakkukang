<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\ApplicationDocument;
use App\Models\ServiceApplication;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DokumenPermohonanController extends Controller
{
    public function download(
        ServiceApplication $permohonan,
        ApplicationDocument $dokumen,
    ): StreamedResponse {
        abort_unless(
            (int) $permohonan->user_id === (int) auth()->id()
            && (int) $dokumen->application_id === (int) $permohonan->id,
            403,
        );

        abort_unless(
            Storage::disk($dokumen->disk)->exists($dokumen->path),
            404,
        );

        return Storage::disk($dokumen->disk)->download(
            $dokumen->path,
            $dokumen->original_name,
            [
                'Content-Type' => $dokumen->mime_type ?: 'application/octet-stream',
            ],
        );
    }
}
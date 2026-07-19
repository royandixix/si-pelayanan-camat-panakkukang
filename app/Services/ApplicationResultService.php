<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Models\ApplicationResult;
use App\Models\ApplicationStatusHistory;
use App\Models\ServiceApplication;
use App\Models\User;
use App\Notifications\NotifikasiMasyarakat;
use BackedEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class ApplicationResultService
{
    public function publish(
        ServiceApplication $application,
        User $actor,
        string $path,
        string $originalName,
        ?string $notes = null,
    ): ApplicationResult {
        $disk = 'local';
        $originalName = Str::limit(
            basename(trim($originalName)),
            255,
            '',
        );

        if (
            $path === ''
            || ! Storage::disk($disk)->exists($path)
        ) {
            throw ValidationException::withMessages([
                'path' => 'Dokumen hasil tidak ditemukan atau gagal disimpan.',
            ]);
        }

        $extension = strtolower(
            pathinfo($originalName, PATHINFO_EXTENSION),
        );

        $mimeType = Storage::disk($disk)->mimeType($path)
            ?: 'application/octet-stream';

        $sizeBytes = Storage::disk($disk)->size($path);

        if (
            $extension !== 'pdf'
            || ! in_array(
                $mimeType,
                ['application/pdf', 'application/x-pdf'],
                true,
            )
        ) {
            Storage::disk($disk)->delete($path);

            throw ValidationException::withMessages([
                'path' => 'Dokumen hasil harus berupa file PDF yang valid.',
            ]);
        }

        if ($sizeBytes > 10485760) {
            Storage::disk($disk)->delete($path);

            throw ValidationException::withMessages([
                'path' => 'Ukuran dokumen hasil maksimal 10 MB.',
            ]);
        }

        $application->loadMissing([
            'result',
            'user',
        ]);

        $pathLama = $application->result?->path;
        $diskLama = $application->result?->disk ?: 'local';

        try {
            $hasil = DB::transaction(function () use (
                $application,
                $actor,
                $path,
                $disk,
                $originalName,
                $mimeType,
                $sizeBytes,
                $notes,
            ): ApplicationResult {
                $permohonan = ServiceApplication::query()
                    ->lockForUpdate()
                    ->findOrFail($application->id);

                $statusLama = $this->statusValue(
                    $permohonan->status,
                );

                $hasil = ApplicationResult::query()
                    ->updateOrCreate(
                        [
                            'application_id' => $permohonan->id,
                        ],
                        [
                            'uploaded_by' => $actor->id,
                            'original_name' => $originalName,
                            'path' => $path,
                            'disk' => $disk,
                            'mime_type' => $mimeType,
                            'size_bytes' => $sizeBytes,
                            'notes' => filled($notes)
                                ? trim((string) $notes)
                                : null,
                            'published_at' => now(),
                        ],
                    );

                $permohonan->update([
                    'status' => ApplicationStatus::COMPLETED->value,
                    'completed_at' => $permohonan->completed_at
                        ?? now(),
                    'rejected_at' => null,
                ]);

                if (
                    $statusLama
                    !== ApplicationStatus::COMPLETED->value
                ) {
                    ApplicationStatusHistory::query()->create([
                        'application_id' => $permohonan->id,
                        'changed_by' => $actor->id,
                        'from_status' => $statusLama,
                        'to_status' => ApplicationStatus::COMPLETED->value,
                        'notes' => 'Dokumen hasil pelayanan telah diterbitkan.',
                        'metadata' => [
                            'sumber' => 'panel_admin',
                            'tindakan' => 'terbitkan_hasil',
                            'result_id' => $hasil->id,
                        ],
                        'created_at' => now(),
                    ]);
                }

                return $hasil;
            });
        } catch (Throwable $exception) {
            if ($path !== $pathLama) {
                Storage::disk($disk)->delete($path);
            }

            throw $exception;
        }

        if (
            $pathLama
            && $pathLama !== $path
            && Storage::disk($diskLama)->exists($pathLama)
        ) {
            Storage::disk($diskLama)->delete($pathLama);
        }

        try {
            $application->user?->notify(
                new NotifikasiMasyarakat(
                    judul: 'Pelayanan telah selesai',
                    pesan: 'Dokumen hasil pelayanan telah diterbitkan dan dapat diunduh melalui detail permohonan.',
                    jenis: 'permohonan',
                    url: route(
                        'masyarakat.permohonan.show',
                        $application,
                        false,
                    ),
                    ikon: 'berhasil',
                ),
            );
        } catch (Throwable $exception) {
            report($exception);
        }

        return $hasil->fresh([
            'application',
            'uploader',
        ]);
    }

    private function statusValue(mixed $status): string
    {
        return $status instanceof BackedEnum
            ? (string) $status->value
            : (string) $status;
    }
}
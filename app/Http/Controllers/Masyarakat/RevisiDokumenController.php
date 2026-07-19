<?php

namespace App\Http\Controllers\Masyarakat;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\ApplicationDocument;
use App\Models\ApplicationStatusHistory;
use App\Models\ServiceApplication;
use App\Models\User;
use App\Notifications\NotifikasiMasyarakat;
use BackedEnum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class RevisiDokumenController extends Controller
{
    public function update(
        Request $request,
        ServiceApplication $permohonan,
        ApplicationDocument $dokumen,
    ): RedirectResponse {
        $pengguna = $this->penggunaMasyarakat($request);

        $this->pastikanMilikPengguna($permohonan, $pengguna);

        abort_unless(
            (int) $dokumen->application_id === (int) $permohonan->id,
            404,
        );

        if (
            $this->nilaiStatus($permohonan->status)
            !== ApplicationStatus::REVISION->value
        ) {
            throw ValidationException::withMessages([
                'berkas' => 'Dokumen tidak dapat diperbarui karena permohonan tidak berstatus perlu perbaikan.',
            ]);
        }

        if (! $this->dokumenPerluRevisi($dokumen)) {
            throw ValidationException::withMessages([
                'berkas' => 'Dokumen ini tidak memerlukan perbaikan.',
            ]);
        }

        $dokumen->loadMissing('requirement');

        $ekstensi = $this->ekstensiDiizinkan($dokumen);
        $maksimumKb = $this->maksimumUkuranKb($dokumen);
        $daftarEkstensi = implode(',', $ekstensi);
        $namaEkstensi = strtoupper(implode(', ', $ekstensi));
        $maksimumMb = round($maksimumKb / 1024, 2);

        $data = $request->validate(
            [
                'dokumen_revisi_id' => [
                    'required',
                    'integer',
                    'in:' . $dokumen->id,
                ],
                'berkas' => [
                    'bail',
                    'required',
                    'file',
                    'mimes:' . $daftarEkstensi,
                    'extensions:' . $daftarEkstensi,
                    'max:' . $maksimumKb,
                ],
            ],
            [
                'dokumen_revisi_id.required' => 'Dokumen revisi tidak ditemukan.',
                'dokumen_revisi_id.in' => 'Dokumen revisi tidak sesuai.',
                'berkas.required' => 'Pilih dokumen pengganti terlebih dahulu.',
                'berkas.file' => 'Dokumen pengganti harus berupa berkas.',
                'berkas.mimes' => "Format dokumen harus {$namaEkstensi}.",
                'berkas.extensions' => "Ekstensi dokumen harus {$namaEkstensi}.",
                'berkas.max' => "Ukuran dokumen maksimal {$maksimumMb} MB.",
            ],
        );

        $berkas = $data['berkas'];

        abort_unless($berkas instanceof UploadedFile, 422);

        $diskBaru = filled($dokumen->disk)
            ? $dokumen->disk
            : 'local';

        $pathBaru = null;
        $pathLama = $dokumen->path;
        $diskLama = filled($dokumen->disk)
            ? $dokumen->disk
            : 'local';

        try {
            $pathBaru = $berkas->store(
                'permohonan/' . $permohonan->id,
                $diskBaru,
            );

            if (! is_string($pathBaru) || $pathBaru === '') {
                throw ValidationException::withMessages([
                    'berkas' => 'Dokumen gagal disimpan. Silakan coba kembali.',
                ]);
            }

            DB::transaction(function () use (
                $dokumen,
                $pengguna,
                $berkas,
                $diskBaru,
                $pathBaru,
            ): void {
                $dokumen->update([
                    'uploaded_by' => $pengguna->id,
                    'original_name' => Str::limit(
                        $berkas->getClientOriginalName(),
                        255,
                        '',
                    ),
                    'path' => $pathBaru,
                    'disk' => $diskBaru,
                    'mime_type' => $berkas->getMimeType(),
                    'size_bytes' => $berkas->getSize(),
                    'verification_status' => 'pending',
                    'verification_notes' => null,
                    'verified_by' => null,
                    'verified_at' => null,
                ]);
            });
        } catch (Throwable $exception) {
            if ($pathBaru) {
                Storage::disk($diskBaru)->delete($pathBaru);
            }

            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            report($exception);

            return back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Dokumen gagal diperbarui',
                    'text' => 'Terjadi kesalahan saat menyimpan dokumen pengganti.',
                    'confirmButtonText' => 'Mengerti',
                ]);
        }

        if ($pathLama && $pathLama !== $pathBaru) {
            Storage::disk($diskLama)->delete($pathLama);
        }

        return redirect()
            ->route('masyarakat.permohonan.show', $permohonan)
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Dokumen diperbarui',
                'text' => 'Dokumen pengganti berhasil diunggah. Periksa dokumen lainnya sebelum mengirim ulang permohonan.',
                'confirmButtonText' => 'Selesai',
            ]);
    }

    public function kirimUlang(
        Request $request,
        ServiceApplication $permohonan,
    ): RedirectResponse {
        $pengguna = $this->penggunaMasyarakat($request);

        $this->pastikanMilikPengguna($permohonan, $pengguna);

        DB::transaction(function () use (
            $permohonan,
            $pengguna,
        ): void {
            $permohonanTerkunci = ServiceApplication::query()
                ->with('documents')
                ->lockForUpdate()
                ->findOrFail($permohonan->id);

            $this->pastikanMilikPengguna(
                $permohonanTerkunci,
                $pengguna,
            );

            if (
                $this->nilaiStatus($permohonanTerkunci->status)
                !== ApplicationStatus::REVISION->value
            ) {
                throw ValidationException::withMessages([
                    'dokumen' => 'Permohonan ini tidak dapat dikirim ulang karena statusnya bukan perlu perbaikan.',
                ]);
            }

            $dokumenBelumDiperbaiki = $permohonanTerkunci
                ->documents
                ->filter(
                    fn (ApplicationDocument $dokumen): bool =>
                        $this->dokumenPerluRevisi($dokumen),
                );

            if ($dokumenBelumDiperbaiki->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'dokumen' => 'Masih ada dokumen yang harus diperbaiki sebelum permohonan dikirim ulang.',
                ]);
            }

            $statusLama = $this->nilaiStatus(
                $permohonanTerkunci->status,
            );

            $permohonanTerkunci->update([
                'status' => ApplicationStatus::VERIFICATION->value,
                'verified_at' => null,
                'rejected_at' => null,
            ]);

            ApplicationStatusHistory::query()->create([
                'application_id' => $permohonanTerkunci->id,
                'changed_by' => $pengguna->id,
                'from_status' => $statusLama,
                'to_status' => ApplicationStatus::VERIFICATION->value,
                'notes' => 'Masyarakat telah memperbaiki dokumen dan mengirim ulang permohonan.',
                'metadata' => [
                    'sumber' => 'portal_masyarakat',
                    'tindakan' => 'kirim_ulang',
                ],
                'created_at' => now(),
            ]);
        });

        $pengguna->notify(
            new NotifikasiMasyarakat(
                judul: 'Permohonan dikirim ulang',
                pesan: 'Dokumen perbaikan telah dikirim dan permohonan kembali menunggu verifikasi petugas.',
                jenis: 'permohonan',
                url: route(
                    'masyarakat.permohonan.show',
                    $permohonan,
                    false,
                ),
                ikon: 'berhasil',
            ),
        );

        return redirect()
            ->route('masyarakat.permohonan.show', $permohonan)
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Permohonan dikirim ulang',
                'text' => 'Dokumen perbaikan telah dikirim dan akan diperiksa kembali oleh petugas.',
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

    private function pastikanMilikPengguna(
        ServiceApplication $permohonan,
        User $pengguna,
    ): void {
        abort_unless(
            (int) $permohonan->user_id === (int) $pengguna->id,
            404,
        );
    }

    private function nilaiStatus(mixed $status): string
    {
        return $status instanceof BackedEnum
            ? (string) $status->value
            : (string) $status;
    }

    private function dokumenPerluRevisi(
        ApplicationDocument $dokumen,
    ): bool {
        $status = $this->nilaiStatus(
            $dokumen->verification_status,
        );

        return filled($dokumen->verification_notes)
            || in_array(
                $status,
                [
                    'revision',
                    'rejected',
                    'invalid',
                    'needs_revision',
                    'need_revision',
                ],
                true,
            );
    }

    private function ekstensiDiizinkan(
        ApplicationDocument $dokumen,
    ): array {
        $ekstensi = collect(
            $dokumen->requirement?->allowed_extensions
                ?: ['pdf', 'jpg', 'jpeg', 'png'],
        )
            ->map(
                fn (mixed $item): string =>
                    strtolower(trim((string) $item)),
            )
            ->filter(
                fn (string $item): bool =>
                    preg_match('/^[a-z0-9]+$/', $item) === 1,
            )
            ->unique()
            ->values()
            ->all();

        return $ekstensi !== []
            ? $ekstensi
            : ['pdf', 'jpg', 'jpeg', 'png'];
    }

    private function maksimumUkuranKb(
        ApplicationDocument $dokumen,
    ): int {
        $maksimum = (int) (
            $dokumen->requirement?->max_size_kb
            ?: 2048
        );

        return max(100, min($maksimum, 10240));
    }
}
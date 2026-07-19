<?php

namespace App\Http\Controllers\Masyarakat;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\ServiceApplication;
use App\Models\User;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class BuktiController extends Controller
{
    public function show(
        Request $request,
        ServiceApplication $permohonan,
    ): View {
        $pengguna = $this->penggunaMasyarakat($request);

        return view(
            'masyarakat.bukti.show',
            $this->dataBukti($permohonan, $pengguna),
        );
    }

    public function download(
        Request $request,
        ServiceApplication $permohonan,
    ): Response {
        $pengguna = $this->penggunaMasyarakat($request);
        $data = $this->dataBukti($permohonan, $pengguna);

        $namaFile = 'bukti-permohonan-'
            . Str::slug($permohonan->registration_number)
            . '.pdf';

        return Pdf::loadView(
            'masyarakat.bukti.pdf',
            $data,
        )
            ->setPaper('a4', 'portrait')
            ->download($namaFile);
    }

    private function dataBukti(
        ServiceApplication $permohonan,
        User $pengguna,
    ): array {
        abort_unless(
            (int) $permohonan->user_id === (int) $pengguna->id,
            404,
        );

        $permohonan->loadMissing([
            'user',
            'service.section',
            'documents.requirement',
            'queue',
        ]);

        $statusValue = $this->statusValue(
            $permohonan->status,
        );

        abort_if(
            $statusValue === ApplicationStatus::DRAFT->value,
            404,
        );

        $daftarDokumen = $permohonan
            ->documents
            ->map(function ($dokumen): array {
                return [
                    'nama' => $dokumen->requirement?->name
                        ?? 'Dokumen persyaratan',
                    'nama_file' => $dokumen->original_name
                        ?: '-',
                    'status' => $this->statusDokumenLabel(
                        $dokumen->verification_status,
                    ),
                    'ukuran' => $this->formatUkuran(
                        (int) $dokumen->size_bytes,
                    ),
                ];
            })
            ->values();

        $labelPengajuan = [
            'purpose' => 'Keperluan',
            'research_title' => 'Judul Penelitian',
            'institution' => 'Instansi',
            'university' => 'Perguruan Tinggi',
            'study_program' => 'Program Studi',
            'research_location' => 'Lokasi Penelitian',
            'start_date' => 'Tanggal Mulai',
            'end_date' => 'Tanggal Selesai',
        ];

        $detailPengajuan = collect(
            $permohonan->applicant_data ?? [],
        )
            ->except([
                'name',
                'nik',
                'email',
                'phone',
                'address',
            ])
            ->map(function (
                mixed $nilai,
                string|int $kunci,
            ) use ($labelPengajuan): ?array {
                $nilaiNormal = $this->normalisasiNilai(
                    $nilai,
                );

                if ($nilaiNormal === null) {
                    return null;
                }

                return [
                    'label' => $labelPengajuan[$kunci]
                        ?? Str::of((string) $kunci)
                            ->replace(['_', '-'], ' ')
                            ->title()
                            ->toString(),
                    'nilai' => $nilaiNormal,
                ];
            })
            ->filter()
            ->values();

        return [
            'permohonan' => $permohonan,
            'pengguna' => $pengguna,
            'statusValue' => $statusValue,
            'statusLabel' => $this->statusLabel(
                $permohonan->status,
            ),
            'tanggalPengajuan' => $permohonan->submitted_at
                ?? $permohonan->created_at,
            'kodeVerifikasi' => $this->kodeVerifikasi(
                $permohonan,
            ),
            'daftarDokumen' => $daftarDokumen,
            'detailPengajuan' => $detailPengajuan,
        ];
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

    private function statusValue(mixed $status): string
    {
        return $status instanceof BackedEnum
            ? (string) $status->value
            : (string) $status;
    }

    private function statusLabel(mixed $status): string
    {
        $value = $this->statusValue($status);
        $case = ApplicationStatus::tryFrom($value);

        if ($case && method_exists($case, 'getLabel')) {
            return (string) $case->getLabel();
        }

        return match ($value) {
            'draft' => 'Draf',
            'submitted' => 'Diajukan',
            'verification' => 'Menunggu Verifikasi',
            'revision' => 'Perlu Perbaikan',
            'processing' => 'Sedang Diproses',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
            default => Str::of($value)
                ->replace('_', ' ')
                ->title()
                ->toString(),
        };
    }

    private function statusDokumenLabel(
        mixed $status,
    ): string {
        return match ($this->statusValue($status)) {
            'pending' => 'Menunggu Pemeriksaan',
            'verified', 'approved', 'valid' => 'Valid',
            'revision', 'needs_revision', 'need_revision' =>
                'Perlu Perbaikan',
            'rejected', 'invalid' => 'Tidak Valid',
            default => 'Menunggu Pemeriksaan',
        };
    }

    private function kodeVerifikasi(
        ServiceApplication $permohonan,
    ): string {
        $payload = implode('|', [
            $permohonan->id,
            $permohonan->registration_number,
            $permohonan->user_id,
            $permohonan->created_at?->timestamp,
        ]);

        $hash = strtoupper(
            substr(
                hash_hmac(
                    'sha256',
                    $payload,
                    (string) config('app.key'),
                ),
                0,
                16,
            ),
        );

        return implode('-', str_split($hash, 4));
    }

    private function normalisasiNilai(
        mixed $nilai,
    ): ?string {
        if (is_bool($nilai)) {
            return $nilai ? 'Ya' : 'Tidak';
        }

        if (is_scalar($nilai)) {
            $hasil = trim((string) $nilai);

            return $hasil !== ''
                ? $hasil
                : null;
        }

        if (is_array($nilai)) {
            $hasil = collect($nilai)
                ->flatten()
                ->filter(
                    fn (mixed $item): bool =>
                        is_scalar($item)
                        && trim((string) $item) !== '',
                )
                ->map(
                    fn (mixed $item): string =>
                        trim((string) $item),
                )
                ->implode(', ');

            return $hasil !== ''
                ? $hasil
                : null;
        }

        return null;
    }

    private function formatUkuran(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 KB';
        }

        if ($bytes >= 1048576) {
            return number_format(
                $bytes / 1048576,
                2,
                ',',
                '.',
            ) . ' MB';
        }

        return number_format(
            $bytes / 1024,
            0,
            ',',
            '.',
        ) . ' KB';
    }
}
<?php

namespace App\Http\Controllers\Masyarakat;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\ApplicationDocument;
use App\Models\Service;
use App\Models\ServiceApplication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class PermohonanController extends Controller
{
    public function index(Request $request): View
    {
        $this->pastikanMasyarakat();

        $cari = trim($request->string('cari')->toString());
        $status = trim($request->string('status')->toString());
        $statusPilihan = $this->statusPilihan();

        if (!array_key_exists($status, $statusPilihan)) {
            $status = '';
        }

        $permohonan = ServiceApplication::query()
            ->with(['service.section'])
            ->withCount('documents')
            ->where('user_id', auth()->id())
            ->when($cari !== '', function (Builder $query) use ($cari): void {
                $query->where(function (Builder $query) use ($cari): void {
                    $query
                        ->where('registration_number', 'like', "%{$cari}%")
                        ->orWhereHas('service', function (Builder $query) use ($cari): void {
                            $query->where('name', 'like', "%{$cari}%");
                        });
                });
            })
            ->when($status !== '', function (Builder $query) use ($status): void {
                $query->where('status', $status);
            })
            ->latest('submitted_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('masyarakat.permohonan.index', compact(
            'permohonan',
            'cari',
            'status',
            'statusPilihan',
        ));
    }

    public function create(Service $layanan): View
    {
        $this->pastikanMasyarakat();

        $layanan->load(['section', 'requirements']);
        $this->pastikanLayananAktif($layanan);

        return view('masyarakat.permohonan.create', compact('layanan'));
    }

    public function store(Request $request, Service $layanan): RedirectResponse
    {
        $this->pastikanMasyarakat();

        $layanan->load(['section', 'requirements']);
        $this->pastikanLayananAktif($layanan);

        $aturan = [
            'keperluan' => ['required', 'string', 'min:10', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'konfirmasi' => ['required', 'accepted'],
            'dokumen' => ['nullable', 'array'],
        ];

        $pesan = [
            'keperluan.required' => 'Keperluan permohonan wajib diisi.',
            'keperluan.min' => 'Keperluan permohonan minimal 10 karakter.',
            'keperluan.max' => 'Keperluan permohonan maksimal 1000 karakter.',
            'catatan.max' => 'Catatan tambahan maksimal 1000 karakter.',
            'konfirmasi.required' => 'Konfirmasi kebenaran data wajib dicentang.',
            'konfirmasi.accepted' => 'Anda harus menyatakan bahwa data yang dimasukkan benar.',
            'dokumen.array' => 'Data dokumen tidak valid.',
        ];

        foreach ($layanan->requirements as $syarat) {
            $ekstensi = collect($syarat->allowed_extensions ?? [])
                ->map(fn ($item): string => strtolower(ltrim((string) $item, '.')))
                ->filter()
                ->values()
                ->all();

            $aturanDokumen = [
                $syarat->is_required ? 'required' : 'nullable',
                'file',
                'max:' . max(1, (int) $syarat->max_size_kb),
            ];

            if ($ekstensi !== []) {
                $aturanDokumen[] = 'mimes:' . implode(',', $ekstensi);
            }

            $aturan["dokumen.{$syarat->id}"] = $aturanDokumen;
            $pesan["dokumen.{$syarat->id}.required"] = "{$syarat->name} wajib diunggah.";
            $pesan["dokumen.{$syarat->id}.file"] = "{$syarat->name} harus berupa berkas.";
            $pesan["dokumen.{$syarat->id}.mimes"] = "Format {$syarat->name} tidak didukung.";
            $pesan["dokumen.{$syarat->id}.max"] = "Ukuran {$syarat->name} melebihi batas maksimal.";
        }

        $data = $request->validate($aturan, $pesan);
        $pengguna = auth()->user();
        $dokumenTersimpan = [];

        try {
            $permohonan = DB::transaction(function () use (
                $request,
                $data,
                $layanan,
                $pengguna,
                &$dokumenTersimpan,
            ): ServiceApplication {
                $permohonan = ServiceApplication::query()->create([
                    'registration_number' => $this->buatNomorRegistrasi(),
                    'user_id' => $pengguna->id,
                    'service_id' => $layanan->id,
                    'assigned_admin_id' => null,
                    'status' => 'submitted',
                    'applicant_data' => [
                        'name' => $pengguna->name,
                        'nik' => $pengguna->nik,
                        'email' => $pengguna->email,
                        'phone' => $pengguna->phone,
                        'address' => $pengguna->address,
                        'purpose' => $data['keperluan'],
                    ],
                    'applicant_notes' => $data['catatan'] ?? null,
                    'internal_notes' => null,
                    'submitted_at' => now(),
                    'verified_at' => null,
                    'completed_at' => null,
                    'rejected_at' => null,
                ]);

                foreach ($layanan->requirements as $syarat) {
                    $berkas = $request->file("dokumen.{$syarat->id}");

                    if (!$berkas) {
                        continue;
                    }

                    $ekstensi = strtolower($berkas->getClientOriginalExtension());
                    $namaTersimpan = Str::uuid()->toString() . ($ekstensi ? ".{$ekstensi}" : '');
                    $path = $berkas->storeAs(
                        "permohonan/{$permohonan->id}",
                        $namaTersimpan,
                        'local',
                    );

                    $dokumenTersimpan[] = [
                        'disk' => 'local',
                        'path' => $path,
                    ];

                    ApplicationDocument::query()->create([
                        'application_id' => $permohonan->id,
                        'requirement_id' => $syarat->id,
                        'uploaded_by' => $pengguna->id,
                        'original_name' => $berkas->getClientOriginalName(),
                        'path' => $path,
                        'disk' => 'local',
                        'mime_type' => $berkas->getMimeType(),
                        'size_bytes' => $berkas->getSize() ?: 0,
                        'verification_status' => 'pending',
                        'verification_notes' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                    ]);
                }

                $permohonan->statusHistories()->create([
                    'changed_by' => $pengguna->id,
                    'from_status' => null,
                    'to_status' => 'submitted',
                    'notes' => 'Permohonan diajukan oleh masyarakat.',
                    'metadata' => null,
                    'created_at' => now(),
                ]);

                return $permohonan;
            });
        } catch (Throwable $exception) {
            foreach ($dokumenTersimpan as $dokumen) {
                Storage::disk($dokumen['disk'])->delete($dokumen['path']);
            }

            report($exception);

            return back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Permohonan gagal dikirim',
                    'text' => 'Terjadi kesalahan saat menyimpan permohonan. Silakan mencoba kembali.',
                    'confirmButtonText' => 'Mengerti',
                ]);
        }

        return redirect()
            ->route('masyarakat.permohonan.show', $permohonan)
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Permohonan berhasil dikirim',
                'text' => "Nomor permohonan Anda adalah {$permohonan->registration_number}.",
                'confirmButtonText' => 'Lihat permohonan',
            ]);
    }

    public function show(ServiceApplication $permohonan): View
    {
        $this->pastikanMasyarakat();

        abort_unless(
            (int) $permohonan->user_id === (int) auth()->id(),
            403,
        );

        $permohonan->load([
            'service.section',
            'documents.requirement',
            'statusHistories.changer',
            'queue',
        ]);

        return view('masyarakat.permohonan.show', compact('permohonan'));
    }

    private function buatNomorRegistrasi(): string
    {
        do {
            $nomor = 'PMH-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (
            ServiceApplication::query()
                ->where('registration_number', $nomor)
                ->exists()
        );

        return $nomor;
    }

    private function statusPilihan(): array
    {
        return [
            'submitted' => 'Diajukan',
            'verification' => 'Menunggu Verifikasi',
            'revision' => 'Perlu Perbaikan',
            'processing' => 'Sedang Diproses',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
        ];
    }

    private function pastikanLayananAktif(Service $layanan): void
    {
        abort_unless(
            $layanan->is_active
            && $layanan->section
            && $layanan->section->is_active,
            404,
        );
    }

    private function pastikanMasyarakat(): void
    {
        $pengguna = auth()->user();

        $role = $pengguna?->role instanceof UserRole
            ? $pengguna->role
            : UserRole::tryFrom((string) $pengguna?->role);

        abort_unless(
            $pengguna
            && $role === UserRole::MASYARAKAT
            && $pengguna->is_active,
            403,
        );
    }
}
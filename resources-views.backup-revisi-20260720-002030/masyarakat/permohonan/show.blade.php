@extends('layouts.masyarakat')

@php
    $statusValue = $permohonan->status instanceof \BackedEnum ? $permohonan->status->value : (string) $permohonan->status;
    $statusData = match ($statusValue) {
        'submitted' => ['Diajukan', 'bg-blue-50 text-blue-700'],
        'verification' => ['Menunggu Verifikasi', 'bg-violet-50 text-violet-700'],
        'revision' => ['Perlu Perbaikan', 'bg-amber-50 text-amber-700'],
        'processing' => ['Sedang Diproses', 'bg-cyan-50 text-cyan-700'],
        'approved' => ['Disetujui', 'bg-indigo-50 text-indigo-700'],
        'rejected' => ['Ditolak', 'bg-red-50 text-red-700'],
        'completed' => ['Selesai', 'bg-emerald-50 text-emerald-700'],
        default => [str($statusValue)->replace('_', ' ')->title(), 'bg-zinc-100 text-zinc-600'],
    };
    $dataPemohon = $permohonan->applicant_data ?? [];
@endphp

@section('judul', $permohonan->registration_number)
@section('deskripsi', 'Detail permohonan ' . $permohonan->registration_number)
@section('breadcrumb', 'Detail Permohonan')
@section('judul_halaman', $permohonan->registration_number)
@section('deskripsi_halaman', $permohonan->service?->name ?? 'Detail permohonan pelayanan')

@section('aksi_header')
<a
    href="{{ route('masyarakat.permohonan.index') }}"
    class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900"
>
    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M19 12H5"></path>
        <path d="m11 18-6-6 6-6"></path>
    </svg>
    Kembali
</a>
@endsection

@section('konten')
@include('masyarakat.permohonan._revisi-dokumen', [
    'permohonan' => $permohonan,
])
@include('masyarakat.permohonan._aksi-bukti', [
    'permohonan' => $permohonan,
])
<div class="grid gap-6 lg:grid-cols-[1fr_340px]">
    <div class="space-y-6">
        <section class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-zinc-400">
                        {{ $permohonan->service?->section?->name ?? '-' }}
                    </p>
                    <h2 class="mt-2 text-xl font-semibold text-zinc-900">
                        {{ $permohonan->service?->name ?? '-' }}
                    </h2>
                    <p class="mt-2 text-xs text-zinc-500">
                        Diajukan {{ $permohonan->submitted_at?->format('d M Y, H:i') ?? '-' }}
                    </p>
                </div>

                <span class="w-fit rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusData[1] }}">
                    {{ $statusData[0] }}
                </span>
            </div>
        </section>

        <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            <header class="border-b border-zinc-200 px-6 py-5">
                <h2 class="text-base font-semibold text-zinc-900">
                    Data permohonan
                </h2>
            </header>

            <div class="grid gap-5 p-6 sm:grid-cols-2">
                <div>
                    <p class="text-xs text-zinc-500">Nama lengkap</p>
                    <p class="mt-1 text-sm font-semibold text-zinc-900">
                        {{ data_get($dataPemohon, 'name', '-') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-zinc-500">NIK</p>
                    <p class="mt-1 text-sm font-semibold text-zinc-900">
                        {{ data_get($dataPemohon, 'nik', '-') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-zinc-500">Email</p>
                    <p class="mt-1 break-all text-sm font-semibold text-zinc-900">
                        {{ data_get($dataPemohon, 'email', '-') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-zinc-500">Nomor telepon</p>
                    <p class="mt-1 text-sm font-semibold text-zinc-900">
                        {{ data_get($dataPemohon, 'phone', '-') }}
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-xs text-zinc-500">Alamat</p>
                    <p class="mt-1 text-sm font-semibold leading-6 text-zinc-900">
                        {{ data_get($dataPemohon, 'address', '-') }}
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-xs text-zinc-500">Keperluan</p>
                    <p class="mt-1 whitespace-pre-line text-sm leading-6 text-zinc-700">
                        {{ data_get($dataPemohon, 'purpose', '-') }}
                    </p>
                </div>

                @if ($permohonan->applicant_notes)
                    <div class="sm:col-span-2">
                        <p class="text-xs text-zinc-500">Catatan tambahan</p>
                        <p class="mt-1 whitespace-pre-line text-sm leading-6 text-zinc-700">
                            {{ $permohonan->applicant_notes }}
                        </p>
                    </div>
                @endif
            </div>
        </section>

        <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            <header class="border-b border-zinc-200 px-6 py-5">
                <h2 class="text-base font-semibold text-zinc-900">
                    Dokumen permohonan
                </h2>
            </header>

            <div class="divide-y divide-zinc-100">
                @forelse ($permohonan->documents as $dokumen)
                    @php
                        $verifikasiValue = $dokumen->verification_status instanceof \BackedEnum
                            ? $dokumen->verification_status->value
                            : (string) $dokumen->verification_status;
                        $verifikasiData = match ($verifikasiValue) {
                            'verified', 'approved' => ['Diterima', 'bg-emerald-50 text-emerald-700'],
                            'rejected' => ['Ditolak', 'bg-red-50 text-red-700'],
                            default => ['Menunggu Pemeriksaan', 'bg-amber-50 text-amber-700'],
                        };
                    @endphp

                    <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-zinc-900">
                                {{ $dokumen->requirement?->name ?? 'Dokumen pendukung' }}
                            </p>
                            <p class="mt-1 truncate text-xs text-zinc-500">
                                {{ $dokumen->original_name }}
                            </p>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $verifikasiData[1] }}">
                                    {{ $verifikasiData[0] }}
                                </span>
                                <span class="text-[11px] text-zinc-400">
                                    {{ number_format($dokumen->size_bytes / 1024, 0, ',', '.') }} KB
                                </span>
                            </div>

                            @if ($dokumen->verification_notes)
                                <p class="mt-2 text-xs leading-5 text-red-600">
                                    {{ $dokumen->verification_notes }}
                                </p>
                            @endif
                        </div>

                        <a
                            href="{{ route('masyarakat.permohonan.dokumen.download', [$permohonan, $dokumen]) }}"
                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-zinc-300 px-4 py-2.5 text-xs font-semibold text-zinc-700 transition hover:border-zinc-900 hover:bg-zinc-900 hover:text-white"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M12 4v12"></path>
                                <path d="m7 11 5 5 5-5"></path>
                                <path d="M5 20h14"></path>
                            </svg>
                            Unduh
                        </a>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-zinc-500">
                            Tidak ada dokumen yang diunggah.
                        </p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            <header class="border-b border-zinc-200 px-6 py-5">
                <h2 class="text-base font-semibold text-zinc-900">
                    Riwayat status
                </h2>
            </header>

            <div class="p-6">
                <div class="space-y-6">
                    @forelse ($permohonan->statusHistories->sortBy('created_at') as $riwayat)
                        @php
                            $riwayatValue = $riwayat->to_status instanceof \BackedEnum
                                ? $riwayat->to_status->value
                                : (string) $riwayat->to_status;
                            $riwayatLabel = match ($riwayatValue) {
                                'submitted' => 'Permohonan diajukan',
                                'verification' => 'Menunggu verifikasi',
                                'revision' => 'Perlu perbaikan',
                                'processing' => 'Sedang diproses',
                                'approved' => 'Permohonan disetujui',
                                'rejected' => 'Permohonan ditolak',
                                'completed' => 'Permohonan selesai',
                                default => str($riwayatValue)->replace('_', ' ')->title(),
                            };
                        @endphp

                        <div class="relative flex gap-4">
                            <span class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-white">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m8 12 2.5 2.5L16 9"></path>
                                </svg>
                            </span>

                            <div>
                                <p class="text-sm font-semibold text-zinc-900">
                                    {{ $riwayatLabel }}
                                </p>
                                <p class="mt-1 text-xs text-zinc-400">
                                    {{ $riwayat->created_at?->format('d M Y, H:i') ?? '-' }}
                                </p>
                                @if ($riwayat->notes)
                                    <p class="mt-2 text-xs leading-5 text-zinc-600">
                                        {{ $riwayat->notes }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500">
                            Riwayat status belum tersedia.
                        </p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>

    <aside>
        <div class="sticky top-24 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
            <h2 class="text-sm font-semibold text-zinc-900">
                Ringkasan
            </h2>

            <dl class="mt-5 divide-y divide-zinc-100">
                <div class="py-3">
                    <dt class="text-xs text-zinc-500">Nomor permohonan</dt>
                    <dd class="mt-1 text-xs font-semibold text-zinc-900">
                        {{ $permohonan->registration_number }}
                    </dd>
                </div>

                <div class="py-3">
                    <dt class="text-xs text-zinc-500">Status</dt>
                    <dd class="mt-2">
                        <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $statusData[1] }}">
                            {{ $statusData[0] }}
                        </span>
                    </dd>
                </div>

                <div class="py-3">
                    <dt class="text-xs text-zinc-500">Tanggal pengajuan</dt>
                    <dd class="mt-1 text-xs font-semibold text-zinc-900">
                        {{ $permohonan->submitted_at?->format('d M Y, H:i') ?? '-' }}
                    </dd>
                </div>

                <div class="py-3">
                    <dt class="text-xs text-zinc-500">Jumlah dokumen</dt>
                    <dd class="mt-1 text-xs font-semibold text-zinc-900">
                        {{ $permohonan->documents->count() }} dokumen
                    </dd>
                </div>

                @if ($permohonan->queue)
                    <div class="py-3">
                        <dt class="text-xs text-zinc-500">Nomor antrean</dt>
                        <dd class="mt-1 text-lg font-semibold text-zinc-900">
                            {{ $permohonan->queue->queue_number }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </aside>
</div>
@endsection 
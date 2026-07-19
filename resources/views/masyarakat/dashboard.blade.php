@extends('layouts.masyarakat')

@php
    $kartuStatistik = [
        [
            'label' => 'Total Permohonan',
            'nilai' => $totalPermohonan,
            'keterangan' => 'Seluruh permohonan Anda',
            'warna' => 'bg-zinc-900',
        ],
        [
            'label' => 'Menunggu Verifikasi',
            'nilai' => $menungguVerifikasi,
            'keterangan' => 'Menunggu pemeriksaan petugas',
            'warna' => 'bg-blue-500',
        ],
        [
            'label' => 'Sedang Diproses',
            'nilai' => $sedangDiproses,
            'keterangan' => 'Dalam proses pelayanan',
            'warna' => 'bg-violet-500',
        ],
        [
            'label' => 'Perlu Perbaikan',
            'nilai' => $perluPerbaikan,
            'keterangan' => 'Memerlukan tindakan Anda',
            'warna' => 'bg-amber-500',
        ],
        [
            'label' => 'Selesai',
            'nilai' => $selesai,
            'keterangan' => 'Pelayanan telah selesai',
            'warna' => 'bg-emerald-500',
        ],
    ];
@endphp

@section('judul', 'Dashboard')
@section('deskripsi', 'Ringkasan aktivitas pelayanan masyarakat.')
@section('breadcrumb', 'Dashboard')
@section('judul_halaman', 'Dashboard Masyarakat')
@section('deskripsi_halaman', 'Pantau permohonan dan aktivitas pelayanan Anda.')

@section('aksi_header')
<a
    href="{{ route('masyarakat.layanan.index') }}"
    class="inline-flex items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-700"
>
    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 5v14"></path>
        <path d="M5 12h14"></path>
    </svg>
    Ajukan layanan
</a>
@endsection

@section('konten')
<section class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
    <div class="grid gap-6 p-6 md:grid-cols-[1fr_auto] md:items-center">
        <div>
            <p class="text-sm font-medium text-zinc-500">
                {{ $sapaan }},
            </p>

            <h2 class="mt-1 text-2xl font-semibold tracking-tight text-zinc-900">
                {{ $pengguna->name }}
            </h2>

            <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-500">
                Selamat datang di Portal Pelayanan Masyarakat Kecamatan Panakkukang. Gunakan dashboard ini untuk memantau permohonan dan antrean pelayanan Anda.
            </p>
        </div>

        <div class="hidden h-20 w-20 items-center justify-center rounded-2xl bg-zinc-900 text-white md:flex">
            <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="M4 21h16"></path>
                <path d="M6 21V9l6-5 6 5v12"></path>
                <path d="M9 21v-6h6v6"></path>
                <path d="M9 11h.01"></path>
                <path d="M15 11h.01"></path>
            </svg>
        </div>
    </div>

    <div class="grid border-t border-zinc-200 sm:grid-cols-3">
        <div class="border-b border-zinc-200 px-6 py-4 sm:border-b-0 sm:border-r">
            <p class="text-[11px] font-medium uppercase tracking-wider text-zinc-400">
                NIK
            </p>

            <p class="mt-1 text-sm font-semibold text-zinc-800">
                {{ $pengguna->nik ?: '-' }}
            </p>
        </div>

        <div class="border-b border-zinc-200 px-6 py-4 sm:border-b-0 sm:border-r">
            <p class="text-[11px] font-medium uppercase tracking-wider text-zinc-400">
                Nomor telepon
            </p>

            <p class="mt-1 text-sm font-semibold text-zinc-800">
                {{ $pengguna->phone ?: '-' }}
            </p>
        </div>

        <div class="px-6 py-4">
            <p class="text-[11px] font-medium uppercase tracking-wider text-zinc-400">
                Email
            </p>

            <p class="mt-1 truncate text-sm font-semibold text-zinc-800">
                {{ $pengguna->email }}
            </p>
        </div>
    </div>
</section>

<section class="mt-6">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-zinc-900">
                Ringkasan permohonan
            </h2>

            <p class="mt-1 text-xs text-zinc-500">
                Data berdasarkan akun yang sedang digunakan.
            </p>
        </div>

        <a
            href="{{ route('masyarakat.permohonan.index') }}"
            class="text-xs font-semibold text-zinc-600 transition hover:text-zinc-950"
        >
            Lihat semua
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        @foreach ($kartuStatistik as $kartu)
            <article class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-zinc-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="h-2.5 w-2.5 rounded-full {{ $kartu['warna'] }}"></span>

                    <svg class="h-4 w-4 text-zinc-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M5 12h14"></path>
                        <path d="m13 6 6 6-6 6"></path>
                    </svg>
                </div>

                <p class="mt-5 text-3xl font-semibold tracking-tight text-zinc-900">
                    {{ number_format($kartu['nilai'], 0, ',', '.') }}
                </p>

                <p class="mt-2 text-sm font-semibold text-zinc-800">
                    {{ $kartu['label'] }}
                </p>

                <p class="mt-1 text-xs leading-5 text-zinc-500">
                    {{ $kartu['keterangan'] }}
                </p>
            </article>
        @endforeach
    </div>
</section>

<div class="mt-6 grid gap-6 lg:grid-cols-[1fr_340px]">
    <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
        <header class="flex items-center justify-between border-b border-zinc-200 px-6 py-5">
            <div>
                <h2 class="text-base font-semibold text-zinc-900">
                    Permohonan terbaru
                </h2>

                <p class="mt-1 text-xs text-zinc-500">
                    Lima permohonan terakhir yang Anda ajukan.
                </p>
            </div>

            <a
                href="{{ route('masyarakat.permohonan.index') }}"
                class="hidden rounded-lg border border-zinc-300 px-3 py-2 text-xs font-semibold text-zinc-600 transition hover:border-zinc-900 hover:text-zinc-950 sm:inline-flex"
            >
                Semua permohonan
            </a>
        </header>

        <div class="divide-y divide-zinc-100">
            @forelse ($permohonanTerbaru as $permohonan)
                @php
                    $statusValue = $permohonan->status instanceof \BackedEnum
                        ? $permohonan->status->value
                        : (string) $permohonan->status;

                    $statusData = match ($statusValue) {
                        'draft' => ['Draf', 'bg-zinc-100 text-zinc-600'],
                        'submitted' => ['Diajukan', 'bg-blue-50 text-blue-700'],
                        'verification' => ['Menunggu Verifikasi', 'bg-violet-50 text-violet-700'],
                        'revision' => ['Perlu Perbaikan', 'bg-amber-50 text-amber-700'],
                        'processing' => ['Sedang Diproses', 'bg-cyan-50 text-cyan-700'],
                        'approved' => ['Disetujui', 'bg-indigo-50 text-indigo-700'],
                        'rejected' => ['Ditolak', 'bg-red-50 text-red-700'],
                        'completed' => ['Selesai', 'bg-emerald-50 text-emerald-700'],
                        'collected' => ['Telah Diambil', 'bg-emerald-50 text-emerald-700'],
                        default => [
                            str($statusValue)->replace('_', ' ')->title(),
                            'bg-zinc-100 text-zinc-600',
                        ],
                    };
                @endphp

                <a
                    href="{{ route('masyarakat.permohonan.show', $permohonan) }}"
                    class="group block px-6 py-5 transition hover:bg-zinc-50"
                >
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-semibold text-zinc-500">
                                    {{ $permohonan->registration_number }}
                                </span>

                                <span class="rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $statusData[1] }}">
                                    {{ $statusData[0] }}
                                </span>
                            </div>

                            <h3 class="mt-2 truncate text-sm font-semibold text-zinc-900 transition group-hover:text-zinc-700">
                                {{ $permohonan->service?->name ?? 'Layanan tidak tersedia' }}
                            </h3>

                            <p class="mt-1 truncate text-xs text-zinc-500">
                                {{ $permohonan->service?->section?->name ?? '-' }}
                            </p>
                        </div>

                        <div class="flex shrink-0 items-center justify-between gap-5 sm:justify-end">
                            <div class="text-left sm:text-right">
                                <p class="text-xs font-medium text-zinc-600">
                                    {{ $permohonan->submitted_at?->format('d M Y') ?? $permohonan->created_at?->format('d M Y') }}
                                </p>

                                <p class="mt-1 text-[11px] text-zinc-400">
                                    {{ $permohonan->documents_count }} dokumen
                                </p>
                            </div>

                            <svg class="h-4 w-4 text-zinc-300 transition group-hover:translate-x-1 group-hover:text-zinc-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"></path>
                                <path d="m13 6 6 6-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-6 py-14 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M6 3h9l3 3v15H6Z"></path>
                            <path d="M14 3v4h4"></path>
                            <path d="M9 12h6"></path>
                            <path d="M9 16h6"></path>
                        </svg>
                    </span>

                    <h3 class="mt-4 text-sm font-semibold text-zinc-900">
                        Belum ada permohonan
                    </h3>

                    <p class="mt-2 text-xs leading-5 text-zinc-500">
                        Pilih layanan untuk membuat permohonan pertama Anda.
                    </p>

                    <a
                        href="{{ route('masyarakat.layanan.index') }}"
                        class="mt-5 inline-flex rounded-lg bg-zinc-900 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-zinc-700"
                    >
                        Pilih layanan
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    <aside class="space-y-6">
        <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-zinc-900">
                        Antrean aktif
                    </h2>

                    <p class="mt-1 text-xs text-zinc-500">
                        Antrean pelayanan terdekat.
                    </p>
                </div>

                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 7v5l3 2"></path>
                    </svg>
                </span>
            </div>

            @if ($antreanAktif)
                @php
                    $statusAntrean = $antreanAktif->status instanceof \BackedEnum
                        ? $antreanAktif->status->value
                        : (string) $antreanAktif->status;

                    $dataStatusAntrean = match ($statusAntrean) {
                        'waiting' => [
                            'Menunggu',
                            'bg-amber-50 text-amber-700',
                        ],
                        'called' => [
                            'Dipanggil',
                            'bg-blue-50 text-blue-700',
                        ],
                        'serving', 'in_service' => [
                            'Sedang Dilayani',
                            'bg-violet-50 text-violet-700',
                        ],
                        'served' => [
                            'Selesai',
                            'bg-emerald-50 text-emerald-700',
                        ],
                        'cancelled' => [
                            'Dibatalkan',
                            'bg-red-50 text-red-700',
                        ],
                        default => [
                            str($statusAntrean)->replace('_', ' ')->title(),
                            'bg-zinc-100 text-zinc-600',
                        ],
                    };
                @endphp

                <div class="mt-5 rounded-xl bg-zinc-900 p-5 text-white">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs text-zinc-400">
                                Nomor antrean
                            </p>

                            <p class="mt-2 text-4xl font-semibold tracking-tight">
                                {{ $antreanAktif->queue_number }}
                            </p>
                        </div>

                        <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $dataStatusAntrean[1] }}">
                            {{ $dataStatusAntrean[0] }}
                        </span>
                    </div>

                    <div class="mt-5 border-t border-white/10 pt-4">
                        <p class="text-sm font-semibold">
                            {{ $antreanAktif->service?->name ?? '-' }}
                        </p>

                        <p class="mt-1 text-xs leading-5 text-zinc-400">
                            {{ $antreanAktif->section?->name ?? '-' }}
                        </p>
                    </div>
                </div>

                <dl class="mt-4 divide-y divide-zinc-100">
                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-xs text-zinc-500">
                            Tanggal
                        </dt>

                        <dd class="text-right text-xs font-semibold text-zinc-800">
                            {{ $antreanAktif->queue_date?->format('d M Y') ?? '-' }}
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-xs text-zinc-500">
                            Urutan
                        </dt>

                        <dd class="text-right text-xs font-semibold text-zinc-800">
                            Ke-{{ $antreanAktif->sequence }}
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-xs text-zinc-500">
                            Status
                        </dt>

                        <dd class="text-right text-xs font-semibold text-zinc-800">
                            {{ $dataStatusAntrean[0] }}
                        </dd>
                    </div>
                </dl>

                <a
                    href="{{ route('masyarakat.antrean.show', $antreanAktif) }}"
                    class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700"
                >
                    Lihat detail antrean

                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14"></path>
                        <path d="m13 6 6 6-6 6"></path>
                    </svg>
                </a>
            @else
                <div class="mt-5 rounded-xl border border-dashed border-zinc-300 px-5 py-8 text-center">
                    <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                        </svg>
                    </span>

                    <p class="mt-3 text-sm font-medium text-zinc-700">
                        Belum ada antrean aktif
                    </p>

                    <p class="mt-2 text-xs leading-5 text-zinc-500">
                        Nomor antrean akan muncul setelah permohonan Anda mendapatkan jadwal pelayanan.
                    </p>

                    <a
                        href="{{ route('masyarakat.antrean.index') }}"
                        class="mt-4 inline-flex items-center justify-center gap-2 text-xs font-semibold text-zinc-700 transition hover:text-zinc-950"
                    >
                        Lihat riwayat antrean

                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14"></path>
                            <path d="m13 6 6 6-6 6"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </section>

        <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-zinc-900">
                    Akses cepat
                </h2>

                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>
                </span>
            </div>

            <div class="mt-4 space-y-2">
                <a
                    href="{{ route('masyarakat.layanan.index') }}"
                    class="group flex items-center justify-between rounded-lg border border-zinc-200 px-4 py-3 text-sm font-medium text-zinc-700 transition hover:border-zinc-900 hover:bg-zinc-50"
                >
                    <span class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500 transition group-hover:bg-zinc-900 group-hover:text-white">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 5h16"></path>
                                <path d="M4 12h16"></path>
                                <path d="M4 19h16"></path>
                            </svg>
                        </span>

                        Pilih layanan
                    </span>

                    <svg class="h-4 w-4 text-zinc-300 transition group-hover:translate-x-1 group-hover:text-zinc-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>

                <a
                    href="{{ route('masyarakat.permohonan.index') }}"
                    class="group flex items-center justify-between rounded-lg border border-zinc-200 px-4 py-3 text-sm font-medium text-zinc-700 transition hover:border-zinc-900 hover:bg-zinc-50"
                >
                    <span class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500 transition group-hover:bg-zinc-900 group-hover:text-white">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-3-7 3V5a2 2 0 0 1 2-2Z"></path>
                            </svg>
                        </span>

                        Permohonan saya
                    </span>

                    <svg class="h-4 w-4 text-zinc-300 transition group-hover:translate-x-1 group-hover:text-zinc-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>

                <a
                    href="{{ route('masyarakat.antrean.index') }}"
                    class="group flex items-center justify-between rounded-lg border border-zinc-200 px-4 py-3 text-sm font-medium text-zinc-700 transition hover:border-zinc-900 hover:bg-zinc-50"
                >
                    <span class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500 transition group-hover:bg-zinc-900 group-hover:text-white">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M5 5h14"></path>
                                <path d="M5 12h14"></path>
                                <path d="M5 19h14"></path>
                                <path d="m16 9 3 3-3 3"></path>
                            </svg>
                        </span>

                        Antrean saya
                    </span>

                    <svg class="h-4 w-4 text-zinc-300 transition group-hover:translate-x-1 group-hover:text-zinc-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>
            </div>
        </section>
    </aside>
</div>
@endsection
@extends('layouts.masyarakat')

@php
    $statusData = match ($statusValue) {
        'waiting' => [
            'Menunggu',
            'bg-amber-50 text-amber-700',
            'Nomor antrean Anda sedang menunggu panggilan petugas.',
        ],
        'called' => [
            'Dipanggil',
            'bg-blue-50 text-blue-700',
            'Nomor antrean Anda telah dipanggil. Segera menuju loket pelayanan.',
        ],
        'serving' => [
            'Sedang Dilayani',
            'bg-violet-50 text-violet-700',
            'Permohonan Anda sedang dilayani oleh petugas.',
        ],
        'completed' => [
            'Selesai',
            'bg-emerald-50 text-emerald-700',
            'Proses pelayanan antrean ini telah selesai.',
        ],
        'cancelled' => [
            'Dibatalkan',
            'bg-red-50 text-red-700',
            'Antrean ini telah dibatalkan.',
        ],
        default => [
            str($statusValue)->replace('_', ' ')->title(),
            'bg-zinc-100 text-zinc-600',
            'Pantau perkembangan antrean melalui halaman ini.',
        ],
    };

    $nomorSedangDilayani = $antreanSedangDilayani?->queue_number;
@endphp

@section('judul', 'Antrean ' . $antrean->queue_number)
@section('deskripsi', 'Detail antrean pelayanan ' . $antrean->queue_number . '.')
@section('breadcrumb', 'Detail Antrean')
@section('judul_halaman', 'Antrean ' . $antrean->queue_number)
@section('deskripsi_halaman', $antrean->service?->name ?? 'Detail antrean pelayanan')

@section('aksi_header')
<a
    href="{{ route('masyarakat.antrean.index') }}"
    class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950"
>
    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M19 12H5"></path>
        <path d="m11 18-6-6 6-6"></path>
    </svg>
    Kembali
</a>
@endsection

@section('konten')
<div class="grid gap-6 lg:grid-cols-[1fr_360px]">
    <div class="space-y-6">
        <section class="overflow-hidden rounded-xl bg-zinc-900 text-white shadow-lg">
            <div class="grid gap-6 p-6 md:grid-cols-[220px_1fr] md:items-center">
                <div class="rounded-xl border border-white/10 bg-white/5 p-6 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-zinc-400">
                        Nomor antrean
                    </p>

                    <p class="mt-3 text-6xl font-semibold tracking-tight">
                        {{ $antrean->queue_number }}
                    </p>

                    <span class="mt-5 inline-flex rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusData[1] }}">
                        {{ $statusData[0] }}
                    </span>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-zinc-400">
                        Layanan
                    </p>

                    <h2 class="mt-2 text-2xl font-semibold">
                        {{ $antrean->service?->name ?? '-' }}
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-zinc-400">
                        {{ $antrean->section?->name ?? '-' }}
                    </p>

                    <div class="mt-6 rounded-lg border border-white/10 bg-white/5 p-4">
                        <p class="text-sm leading-6 text-zinc-300">
                            {{ $statusData[2] }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        @if ($statusValue === 'waiting')
            <section class="grid gap-4 sm:grid-cols-2">
                <article class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-medium uppercase tracking-wider text-zinc-400">
                        Antrean di depan Anda
                    </p>

                    <p class="mt-3 text-4xl font-semibold tracking-tight text-zinc-900">
                        {{ $jumlahDiDepan }}
                    </p>

                    <p class="mt-2 text-xs leading-5 text-zinc-500">
                        Jumlah antrean aktif dengan urutan sebelum nomor Anda.
                    </p>
                </article>

                <article class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-medium uppercase tracking-wider text-zinc-400">
                        Sedang dipanggil
                    </p>

                    <p class="mt-3 text-4xl font-semibold tracking-tight text-zinc-900">
                        {{ $nomorSedangDilayani ?: '-' }}
                    </p>

                    <p class="mt-2 text-xs leading-5 text-zinc-500">
                        Nomor yang sedang dipanggil atau dilayani pada seksi ini.
                    </p>
                </article>
            </section>
        @endif

        <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            <header class="border-b border-zinc-200 px-6 py-5">
                <h2 class="text-base font-semibold text-zinc-900">
                    Informasi antrean
                </h2>

                <p class="mt-1 text-xs text-zinc-500">
                    Informasi jadwal dan pelayanan antrean Anda.
                </p>
            </header>

            <dl class="grid gap-0 sm:grid-cols-2">
                <div class="border-b border-zinc-100 px-6 py-5 sm:border-r">
                    <dt class="text-xs text-zinc-500">
                        Tanggal pelayanan
                    </dt>

                    <dd class="mt-2 text-sm font-semibold text-zinc-900">
                        {{ $antrean->queue_date?->translatedFormat('l, d F Y') ?? '-' }}
                    </dd>
                </div>

                <div class="border-b border-zinc-100 px-6 py-5">
                    <dt class="text-xs text-zinc-500">
                        Urutan
                    </dt>

                    <dd class="mt-2 text-sm font-semibold text-zinc-900">
                        Urutan ke-{{ $antrean->sequence }}
                    </dd>
                </div>

                <div class="border-b border-zinc-100 px-6 py-5 sm:border-r">
                    <dt class="text-xs text-zinc-500">
                        Seksi pelayanan
                    </dt>

                    <dd class="mt-2 text-sm font-semibold leading-6 text-zinc-900">
                        {{ $antrean->section?->name ?? '-' }}
                    </dd>
                </div>

                <div class="border-b border-zinc-100 px-6 py-5">
                    <dt class="text-xs text-zinc-500">
                        Nama layanan
                    </dt>

                    <dd class="mt-2 text-sm font-semibold leading-6 text-zinc-900">
                        {{ $antrean->service?->name ?? '-' }}
                    </dd>
                </div>

                <div class="border-b border-zinc-100 px-6 py-5 sm:col-span-2">
                    <dt class="text-xs text-zinc-500">
                        Nomor permohonan
                    </dt>

                    <dd class="mt-2">
                        @if ($antrean->application)
                            <a
                                href="{{ route('masyarakat.permohonan.show', $antrean->application) }}"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-zinc-900 underline decoration-zinc-300 underline-offset-4 transition hover:decoration-zinc-900"
                            >
                                {{ $antrean->application->registration_number }}

                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14"></path>
                                    <path d="m13 6 6 6-6 6"></path>
                                </svg>
                            </a>
                        @else
                            <span class="text-sm font-semibold text-zinc-900">
                                -
                            </span>
                        @endif
                    </dd>
                </div>
            </dl>
        </section>

        <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            <header class="border-b border-zinc-200 px-6 py-5">
                <h2 class="text-base font-semibold text-zinc-900">
                    Perkembangan antrean
                </h2>

                <p class="mt-1 text-xs text-zinc-500">
                    Waktu setiap tahapan pelayanan antrean.
                </p>
            </header>

            <div class="p-6">
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m8 12 2.5 2.5L16 9"></path>
                            </svg>
                        </span>

                        <div>
                            <p class="text-sm font-semibold text-zinc-900">
                                Antrean didaftarkan
                            </p>

                            <p class="mt-1 text-xs text-zinc-500">
                                {{ $antrean->registered_at?->format('d M Y, H:i') ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <span @class([
                            'flex h-9 w-9 shrink-0 items-center justify-center rounded-full',
                            'bg-blue-100 text-blue-700' => $antrean->called_at,
                            'bg-zinc-100 text-zinc-400' => !$antrean->called_at,
                        ])>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z"></path>
                            </svg>
                        </span>

                        <div>
                            <p class="text-sm font-semibold text-zinc-900">
                                Nomor dipanggil
                            </p>

                            <p class="mt-1 text-xs text-zinc-500">
                                {{ $antrean->called_at?->format('d M Y, H:i') ?? 'Belum dipanggil' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <span @class([
                            'flex h-9 w-9 shrink-0 items-center justify-center rounded-full',
                            'bg-violet-100 text-violet-700' => $antrean->service_started_at,
                            'bg-zinc-100 text-zinc-400' => !$antrean->service_started_at,
                        ])>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 7v5l3 2"></path>
                            </svg>
                        </span>

                        <div>
                            <p class="text-sm font-semibold text-zinc-900">
                                Mulai dilayani
                            </p>

                            <p class="mt-1 text-xs text-zinc-500">
                                {{ $antrean->service_started_at?->format('d M Y, H:i') ?? 'Belum dilayani' }}
                            </p>
                        </div>
                    </div>

                    @if ($statusValue === 'cancelled')
                        <div class="flex gap-4">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m7 7 10 10"></path>
                                    <path d="m17 7-10 10"></path>
                                </svg>
                            </span>

                            <div>
                                <p class="text-sm font-semibold text-zinc-900">
                                    Antrean dibatalkan
                                </p>

                                <p class="mt-1 text-xs text-zinc-500">
                                    {{ $antrean->cancelled_at?->format('d M Y, H:i') ?? '-' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="flex gap-4">
                            <span @class([
                                'flex h-9 w-9 shrink-0 items-center justify-center rounded-full',
                                'bg-emerald-100 text-emerald-700' => $antrean->served_at,
                                'bg-zinc-100 text-zinc-400' => !$antrean->served_at,
                            ])>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m8 12 2.5 2.5L16 9"></path>
                                </svg>
                            </span>

                            <div>
                                <p class="text-sm font-semibold text-zinc-900">
                                    Pelayanan selesai
                                </p>

                                <p class="mt-1 text-xs text-zinc-500">
                                    {{ $antrean->served_at?->format('d M Y, H:i') ?? 'Belum selesai' }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <aside class="space-y-6">
        <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
            <h2 class="text-sm font-semibold text-zinc-900">
                Ringkasan
            </h2>

            <dl class="mt-5 divide-y divide-zinc-100">
                <div class="py-3">
                    <dt class="text-xs text-zinc-500">
                        Nomor antrean
                    </dt>

                    <dd class="mt-1 text-lg font-semibold text-zinc-900">
                        {{ $antrean->queue_number }}
                    </dd>
                </div>

                <div class="py-3">
                    <dt class="text-xs text-zinc-500">
                        Status
                    </dt>

                    <dd class="mt-2">
                        <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $statusData[1] }}">
                            {{ $statusData[0] }}
                        </span>
                    </dd>
                </div>

                <div class="py-3">
                    <dt class="text-xs text-zinc-500">
                        Tanggal
                    </dt>

                    <dd class="mt-1 text-xs font-semibold text-zinc-900">
                        {{ $antrean->queue_date?->format('d M Y') ?? '-' }}
                    </dd>
                </div>

                <div class="py-3">
                    <dt class="text-xs text-zinc-500">
                        Waktu pendaftaran
                    </dt>

                    <dd class="mt-1 text-xs font-semibold text-zinc-900">
                        {{ $antrean->registered_at?->format('H:i') ?? '-' }}
                    </dd>
                </div>
            </dl>
        </section>

        <section class="rounded-xl border border-amber-200 bg-amber-50 p-5">
            <div class="flex gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 8v4"></path>
                        <path d="M12 16h.01"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                    </svg>
                </span>

                <div>
                    <h2 class="text-sm font-semibold text-amber-900">
                        Perhatian
                    </h2>

                    <p class="mt-2 text-xs leading-5 text-amber-800">
                        Datang lebih awal dan siapkan dokumen asli yang berkaitan dengan permohonan Anda. Pantau status antrean secara berkala.
                    </p>
                </div>
            </div>
        </section>
    </aside>
</div>
@endsection
@extends('layouts.masyarakat')

@php
    $filterAntrean = [
        [
            'label' => 'Semua',
            'nilai' => '',
            'jumlah' => $totalAntrean,
        ],
        [
            'label' => 'Menunggu',
            'nilai' => 'waiting',
            'jumlah' => $menunggu,
        ],
        [
            'label' => 'Dipanggil',
            'nilai' => 'called',
            'jumlah' => $dipanggil,
        ],
        [
            'label' => 'Sedang Dilayani',
            'nilai' => 'serving',
            'jumlah' => $sedangDilayani,
        ],
        [
            'label' => 'Selesai',
            'nilai' => 'served',
            'jumlah' => $selesai,
        ],
    ];
@endphp

@section('judul', 'Antrean Saya')
@section('deskripsi', 'Pantau jadwal dan perkembangan antrean pelayanan Anda.')
@section('breadcrumb', 'Antrean')
@section('judul_halaman', 'Antrean Saya')
@section('deskripsi_halaman', 'Pantau nomor, jadwal, dan perkembangan antrean pelayanan Anda.')

@section('aksi_header')
<a
    href="{{ route('masyarakat.permohonan.index') }}"
    class="inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950"
>
    <svg
        class="h-4 w-4"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
    >
        <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-3-7 3V5a2 2 0 0 1 2-2Z"></path>
    </svg>

    Permohonan saya
</a>
@endsection

@section('konten')
@if ($antreanAktif)
    @php
        $statusAktif = $antreanAktif->status instanceof \BackedEnum
            ? $antreanAktif->status->value
            : (string) $antreanAktif->status;

        $dataStatusAktif = match ($statusAktif) {
            'waiting' => [
                'Menunggu',
                'bg-amber-400 text-amber-950',
                'Nomor Anda sedang menunggu panggilan petugas.',
                1,
            ],
            'called' => [
                'Dipanggil',
                'bg-blue-400 text-blue-950',
                'Nomor Anda telah dipanggil. Segera menuju loket pelayanan.',
                2,
            ],
            'serving', 'in_service' => [
                'Sedang Dilayani',
                'bg-violet-400 text-violet-950',
                'Permohonan Anda sedang dilayani oleh petugas.',
                3,
            ],
            'served' => [
                'Selesai',
                'bg-emerald-400 text-emerald-950',
                'Proses pelayanan antrean telah selesai.',
                4,
            ],
            default => [
                str($statusAktif)->replace('_', ' ')->title(),
                'bg-white/20 text-white',
                'Pantau perkembangan antrean melalui halaman ini.',
                1,
            ],
        };

        $tahapAktif = $dataStatusAktif[3];
    @endphp

    <section class="overflow-hidden rounded-xl bg-zinc-900 text-white shadow-lg">
        <div class="grid gap-6 p-6 lg:grid-cols-[220px_1fr_auto] lg:items-center">
            <div class="rounded-xl border border-white/10 bg-white/5 p-5 text-center">
                <p class="text-xs font-medium uppercase tracking-wider text-zinc-400">
                    Nomor antrean
                </p>

                <p class="mt-3 text-5xl font-semibold tracking-tight">
                    {{ $antreanAktif->queue_number }}
                </p>

                <span class="mt-4 inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $dataStatusAktif[1] }}">
                    {{ $dataStatusAktif[0] }}
                </span>
            </div>

            <div class="min-w-0">
                <p class="text-xs font-medium uppercase tracking-wider text-zinc-400">
                    Antrean pelayanan aktif
                </p>

                <h2 class="mt-2 text-xl font-semibold">
                    {{ $antreanAktif->service?->name ?? '-' }}
                </h2>

                <p class="mt-2 text-sm leading-6 text-zinc-400">
                    {{ $antreanAktif->section?->name ?? '-' }}
                </p>

                <p class="mt-4 text-sm leading-6 text-zinc-300">
                    {{ $dataStatusAktif[2] }}
                </p>

                <div class="mt-5 flex flex-wrap gap-x-6 gap-y-3 text-xs text-zinc-300">
                    <span class="inline-flex items-center gap-2">
                        <svg
                            class="h-4 w-4 text-zinc-500"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                            <path d="M16 3v4"></path>
                            <path d="M8 3v4"></path>
                            <path d="M3 10h18"></path>
                        </svg>

                        {{ $antreanAktif->queue_date?->format('d M Y') ?? '-' }}
                    </span>

                    <span class="inline-flex items-center gap-2">
                        <svg
                            class="h-4 w-4 text-zinc-500"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M5 5h14"></path>
                            <path d="M5 12h14"></path>
                            <path d="M5 19h14"></path>
                        </svg>

                        Urutan ke-{{ $antreanAktif->sequence }}
                    </span>
                </div>
            </div>

            <a
                href="{{ route('masyarakat.antrean.show', $antreanAktif) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-semibold text-zinc-900 transition hover:bg-zinc-200"
            >
                Lihat detail

                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </a>
        </div>

        <div class="border-t border-white/10 px-6 py-5">
            <div class="grid gap-4 sm:grid-cols-4">
                @foreach ([
                    ['nomor' => 1, 'label' => 'Menunggu'],
                    ['nomor' => 2, 'label' => 'Dipanggil'],
                    ['nomor' => 3, 'label' => 'Dilayani'],
                    ['nomor' => 4, 'label' => 'Selesai'],
                ] as $tahap)
                    <div class="flex items-center gap-3">
                        <span @class([
                            'flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-semibold',
                            'bg-white text-zinc-900' => $tahapAktif >= $tahap['nomor'],
                            'border border-white/20 bg-white/5 text-zinc-500' => $tahapAktif < $tahap['nomor'],
                        ])>
                            @if ($tahapAktif > $tahap['nomor'])
                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path d="m8 12 2.5 2.5L16 9"></path>
                                </svg>
                            @else
                                {{ $tahap['nomor'] }}
                            @endif
                        </span>

                        <span @class([
                            'text-xs font-medium',
                            'text-white' => $tahapAktif >= $tahap['nomor'],
                            'text-zinc-500' => $tahapAktif < $tahap['nomor'],
                        ])>
                            {{ $tahap['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@else
    <section class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
        <div class="grid gap-6 p-6 md:grid-cols-[auto_1fr_auto] md:items-center">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400">
                <svg
                    class="h-7 w-7"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.7"
                >
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 7v5l3 2"></path>
                </svg>
            </span>

            <div>
                <h2 class="text-base font-semibold text-zinc-900">
                    Belum ada antrean aktif
                </h2>

                <p class="mt-1 max-w-2xl text-sm leading-6 text-zinc-500">
                    Antrean akan muncul setelah permohonan Anda diverifikasi dan petugas menetapkan jadwal pelayanan.
                </p>
            </div>

            <a
                href="{{ route('masyarakat.permohonan.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-700"
            >
                Periksa permohonan

                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </a>
        </div>
    </section>
@endif

<section class="mt-6 rounded-xl border border-zinc-200 bg-white shadow-sm">
    <header class="border-b border-zinc-200 px-6 py-5">
        <div>
            <p class="text-[11px] font-medium uppercase tracking-wider text-zinc-400">
                Proses pelayanan
            </p>

            <h2 class="mt-1 text-base font-semibold text-zinc-900">
                Bagaimana antrean bekerja?
            </h2>

            <p class="mt-1 text-xs leading-5 text-zinc-500">
                Antrean dibuat oleh petugas setelah permohonan memenuhi persyaratan pelayanan.
            </p>
        </div>
    </header>

    <div class="grid divide-y divide-zinc-100 md:grid-cols-4 md:divide-x md:divide-y-0">
        <article class="p-5">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-900 text-sm font-semibold text-white">
                    1
                </span>

                <svg
                    class="hidden h-4 w-4 text-zinc-300 md:block"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </div>

            <h3 class="mt-4 text-sm font-semibold text-zinc-900">
                Jadwal ditetapkan
            </h3>

            <p class="mt-2 text-xs leading-5 text-zinc-500">
                Petugas menetapkan tanggal dan nomor antrean untuk permohonan Anda.
            </p>
        </article>

        <article class="p-5">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-100 text-sm font-semibold text-zinc-700">
                    2
                </span>

                <svg
                    class="hidden h-4 w-4 text-zinc-300 md:block"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </div>

            <h3 class="mt-4 text-sm font-semibold text-zinc-900">
                Menunggu panggilan
            </h3>

            <p class="mt-2 text-xs leading-5 text-zinc-500">
                Datang sesuai jadwal dan pantau nomor antrean yang sedang berjalan.
            </p>
        </article>

        <article class="p-5">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-100 text-sm font-semibold text-zinc-700">
                    3
                </span>

                <svg
                    class="hidden h-4 w-4 text-zinc-300 md:block"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </div>

            <h3 class="mt-4 text-sm font-semibold text-zinc-900">
                Pelayanan dimulai
            </h3>

            <p class="mt-2 text-xs leading-5 text-zinc-500">
                Segera menuju loket ketika nomor antrean Anda dipanggil.
            </p>
        </article>

        <article class="p-5">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-100 text-sm font-semibold text-zinc-700">
                4
            </span>

            <h3 class="mt-4 text-sm font-semibold text-zinc-900">
                Pelayanan selesai
            </h3>

            <p class="mt-2 text-xs leading-5 text-zinc-500">
                Status antrean berubah menjadi selesai setelah pelayanan diberikan.
            </p>
        </article>
    </div>
</section>

<section class="mt-6 rounded-xl border border-zinc-200 bg-white shadow-sm">
    <header class="border-b border-zinc-200 px-5 py-5 sm:px-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-base font-semibold text-zinc-900">
                        Riwayat antrean
                    </h2>

                    <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-[10px] font-semibold text-zinc-600">
                        {{ number_format($antrean->total(), 0, ',', '.') }} data
                    </span>
                </div>

                <p class="mt-1 text-xs text-zinc-500">
                    Seluruh jadwal antrean yang pernah Anda terima.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach ($filterAntrean as $itemFilter)
                    <a
                        href="{{ $itemFilter['nilai'] === ''
                            ? route('masyarakat.antrean.index')
                            : route('masyarakat.antrean.index', ['status' => $itemFilter['nilai']]) }}"
                        @class([
                            'inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-xs font-semibold transition',
                            'border-zinc-900 bg-zinc-900 text-white' => $status === $itemFilter['nilai'],
                            'border-zinc-200 bg-white text-zinc-600 hover:border-zinc-400 hover:text-zinc-950' => $status !== $itemFilter['nilai'],
                        ])
                    >
                        {{ $itemFilter['label'] }}

                        <span @class([
                            'rounded-full px-1.5 py-0.5 text-[9px]',
                            'bg-white/20 text-white' => $status === $itemFilter['nilai'],
                            'bg-zinc-100 text-zinc-500' => $status !== $itemFilter['nilai'],
                        ])>
                            {{ $itemFilter['jumlah'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </header>

    <div class="divide-y divide-zinc-100">
        @forelse ($antrean as $item)
            @php
                $statusValue = $item->status instanceof \BackedEnum
                    ? $item->status->value
                    : (string) $item->status;

                $statusData = match ($statusValue) {
                    'waiting' => [
                        'Menunggu',
                        'bg-amber-50 text-amber-700',
                        'bg-amber-500',
                    ],
                    'called' => [
                        'Dipanggil',
                        'bg-blue-50 text-blue-700',
                        'bg-blue-500',
                    ],
                    'serving', 'in_service' => [
                        'Sedang Dilayani',
                        'bg-violet-50 text-violet-700',
                        'bg-violet-500',
                    ],
                    'served' => [
                        'Selesai',
                        'bg-emerald-50 text-emerald-700',
                        'bg-emerald-500',
                    ],
                    'cancelled' => [
                        'Dibatalkan',
                        'bg-red-50 text-red-700',
                        'bg-red-500',
                    ],
                    default => [
                        str($statusValue)->replace('_', ' ')->title(),
                        'bg-zinc-100 text-zinc-600',
                        'bg-zinc-400',
                    ],
                };
            @endphp

            <a
                href="{{ route('masyarakat.antrean.show', $item) }}"
                class="group block px-5 py-5 transition hover:bg-zinc-50 sm:px-6"
            >
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex min-w-0 items-center gap-4">
                        <div class="relative shrink-0">
                            <span class="flex h-14 min-w-14 items-center justify-center rounded-xl bg-zinc-900 px-3 text-lg font-semibold text-white">
                                {{ $item->queue_number }}
                            </span>

                            <span class="absolute -right-1 -top-1 h-3 w-3 rounded-full border-2 border-white {{ $statusData[2] }}"></span>
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="truncate text-sm font-semibold text-zinc-900">
                                    {{ $item->service?->name ?? '-' }}
                                </h3>

                                <span class="rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $statusData[1] }}">
                                    {{ $statusData[0] }}
                                </span>
                            </div>

                            <p class="mt-1 truncate text-xs text-zinc-500">
                                {{ $item->section?->name ?? '-' }}
                            </p>

                            <p class="mt-2 text-[11px] text-zinc-400">
                                Permohonan {{ $item->application?->registration_number ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center justify-between gap-6 sm:justify-end">
                        <div class="text-left sm:text-right">
                            <p class="text-xs font-semibold text-zinc-700">
                                {{ $item->queue_date?->format('d M Y') ?? '-' }}
                            </p>

                            <p class="mt-1 text-[11px] text-zinc-400">
                                Urutan ke-{{ $item->sequence }}
                            </p>
                        </div>

                        <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-200 text-zinc-400 transition group-hover:border-zinc-900 group-hover:bg-zinc-900 group-hover:text-white">
                            <svg
                                class="h-4 w-4 transition group-hover:translate-x-0.5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M5 12h14"></path>
                                <path d="m13 6 6 6-6 6"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="px-6 py-16 text-center">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400">
                    <svg
                        class="h-7 w-7"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                    >
                        <path d="M5 4h14v16H5Z"></path>
                        <path d="M8 8h8"></path>
                        <path d="M8 12h8"></path>
                        <path d="M8 16h5"></path>
                    </svg>
                </span>

                <h3 class="mt-4 text-sm font-semibold text-zinc-900">
                    @if ($status !== '')
                        Tidak ada antrean dengan status ini
                    @else
                        Belum ada riwayat antrean
                    @endif
                </h3>

                <p class="mx-auto mt-2 max-w-md text-xs leading-5 text-zinc-500">
                    @if ($status !== '')
                        Pilih status lainnya atau tampilkan seluruh riwayat antrean.
                    @else
                        Antrean yang diberikan oleh petugas akan tampil pada halaman ini.
                    @endif
                </p>

                @if ($status !== '')
                    <a
                        href="{{ route('masyarakat.antrean.index') }}"
                        class="mt-5 inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-zinc-700"
                    >
                        Tampilkan semua antrean
                    </a>
                @endif
            </div>
        @endforelse
    </div>
</section>

@if ($antrean->hasPages())
    <div class="mt-7">
        {{ $antrean->links() }}
    </div>
@endif

<section class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-5">
    <div class="flex items-start gap-4">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
            <svg
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path d="M12 8v4"></path>
                <path d="M12 16h.01"></path>
                <circle cx="12" cy="12" r="9"></circle>
            </svg>
        </span>

        <div>
            <h2 class="text-sm font-semibold text-amber-900">
                Persiapan sebelum pelayanan
            </h2>

            <p class="mt-2 text-xs leading-5 text-amber-800">
                Datang sebelum waktu pelayanan, bawa dokumen identitas dan berkas asli yang berkaitan dengan permohonan, serta pantau nomor antrean secara berkala.
            </p>
        </div>
    </div>
</section>
@endsection
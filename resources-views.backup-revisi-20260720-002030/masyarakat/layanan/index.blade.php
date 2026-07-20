@extends('layouts.masyarakat')

@php
    $nilaiCari = $cari ?? request('cari', '');
    $nilaiSeksi = $seksi ?? request('seksi', '');
@endphp

@section('judul', 'Layanan')
@section('deskripsi', 'Daftar layanan masyarakat Kecamatan Panakkukang.')
@section('breadcrumb', 'Layanan')
@section('judul_halaman', 'Layanan Masyarakat')
@section('deskripsi_halaman', 'Pilih layanan sesuai kebutuhan dan lengkapi persyaratan permohonannya.')

@section('aksi_header')
<a
    href="{{ route('masyarakat.permohonan.index') }}"
    class="inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950"
>
    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-3-7 3V5a2 2 0 0 1 2-2Z"></path>
    </svg>
    Permohonan saya
</a>
@endsection

@section('konten')
<section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
    <form
        method="GET"
        action="{{ route('masyarakat.layanan.index') }}"
        class="grid gap-3 lg:grid-cols-[1fr_280px_auto]"
    >
        <div class="relative">
            <svg
                class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-zinc-400"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <circle cx="11" cy="11" r="7"></circle>
                <path d="m20 20-3.5-3.5"></path>
            </svg>

            <input
                type="search"
                name="cari"
                value="{{ $nilaiCari }}"
                placeholder="Cari nama atau kode layanan"
                class="block w-full rounded-lg border border-zinc-300 bg-white py-3 pl-12 pr-4 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200"
            >
        </div>

        <select
            name="seksi"
            class="block w-full rounded-lg border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-700 outline-none transition focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200"
        >
            <option value="">Semua seksi pelayanan</option>

            @foreach ($daftarSeksi as $itemSeksi)
                <option
                    value="{{ $itemSeksi->id }}"
                    @selected((string) $nilaiSeksi === (string) $itemSeksi->id)
                >
                    {{ $itemSeksi->name }}
                </option>
            @endforeach
        </select>

        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-zinc-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M4 5h16"></path>
                <path d="M7 12h10"></path>
                <path d="M10 19h4"></path>
            </svg>
            Terapkan
        </button>
    </form>

    @if ($nilaiCari !== '' || $nilaiSeksi !== '')
        <div class="mt-4 flex flex-col gap-3 border-t border-zinc-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-zinc-500">
                Ditemukan {{ number_format($layanan->total(), 0, ',', '.') }} layanan
            </p>

            <a
                href="{{ route('masyarakat.layanan.index') }}"
                class="inline-flex items-center gap-2 text-xs font-semibold text-zinc-700 transition hover:text-zinc-950"
            >
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m6 6 12 12"></path>
                    <path d="m18 6-12 12"></path>
                </svg>
                Hapus filter
            </a>
        </div>
    @endif
</section>

<section class="mt-6">
    <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-base font-semibold text-zinc-900">
                Daftar layanan
            </h2>

            <p class="mt-1 text-xs leading-5 text-zinc-500">
                Buka detail layanan untuk melihat informasi dan persyaratan pengajuan.
            </p>
        </div>

        <p class="text-xs font-medium text-zinc-500">
            {{ number_format($layanan->total(), 0, ',', '.') }} layanan tersedia
        </p>
    </div>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($layanan as $item)
            <article class="group flex h-full flex-col overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-zinc-300 hover:shadow-md">
                <div class="h-1 bg-gradient-to-r from-zinc-900 via-zinc-500 to-zinc-200"></div>

                <div class="flex flex-1 flex-col p-5">
                    <div class="flex items-start justify-between gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-zinc-900 text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M6 3h9l3 3v15H6Z"></path>
                                <path d="M14 3v4h4"></path>
                                <path d="M9 12h6"></path>
                                <path d="M9 16h6"></path>
                            </svg>
                        </span>

                        @if ($item->queue_enabled)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Antrean tersedia
                            </span>
                        @else
                            <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-[10px] font-semibold text-zinc-500">
                                Tanpa antrean
                            </span>
                        @endif
                    </div>

                    <div class="mt-5">
                        <p class="text-[11px] font-medium uppercase tracking-wider text-zinc-400">
                            {{ $item->section?->name ?? 'Seksi pelayanan' }}
                        </p>

                        <h3 class="mt-2 text-base font-semibold leading-6 text-zinc-900">
                            {{ $item->name }}
                        </h3>

                        @if ($item->code)
                            <p class="mt-1 text-[11px] font-medium text-zinc-400">
                                {{ $item->code }}
                            </p>
                        @endif

                        <p class="mt-4 min-h-15 text-sm leading-6 text-zinc-500">
                            {{ $item->description ? \Illuminate\Support\Str::limit($item->description, 150) : 'Informasi lengkap mengenai layanan dan persyaratan pengajuan tersedia pada halaman detail.' }}
                        </p>
                    </div>

                    <div class="mt-5 grid grid-cols-2 divide-x divide-zinc-200 rounded-lg border border-zinc-200 bg-zinc-50">
                        <div class="px-3 py-3">
                            <p class="text-[10px] font-medium uppercase tracking-wider text-zinc-400">
                                Persyaratan
                            </p>

                            <p class="mt-1 text-xs font-semibold text-zinc-800">
                                {{ $item->requirements_count }} dokumen
                            </p>
                        </div>

                        <div class="px-3 py-3">
                            <p class="text-[10px] font-medium uppercase tracking-wider text-zinc-400">
                                Estimasi
                            </p>

                            <p class="mt-1 text-xs font-semibold text-zinc-800">
                                @if ($item->processing_days)
                                    {{ $item->processing_days }} hari kerja
                                @else
                                    Sesuai verifikasi
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mt-auto pt-5">
                        <a
                            href="{{ route('masyarakat.layanan.show', $item->slug) }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700"
                        >
                            Lihat detail layanan

                            <svg
                                class="h-4 w-4 transition group-hover:translate-x-1"
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
                </div>
            </article>
        @empty
            <div class="md:col-span-2 xl:col-span-3">
                <section class="rounded-xl border border-dashed border-zinc-300 bg-white px-6 py-16 text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M4 5h16"></path>
                            <path d="M4 12h16"></path>
                            <path d="M4 19h16"></path>
                        </svg>
                    </span>

                    <h2 class="mt-4 text-sm font-semibold text-zinc-900">
                        Layanan tidak ditemukan
                    </h2>

                    <p class="mt-2 text-xs leading-5 text-zinc-500">
                        Ubah kata pencarian atau pilih seksi pelayanan lainnya.
                    </p>

                    <a
                        href="{{ route('masyarakat.layanan.index') }}"
                        class="mt-5 inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-zinc-700"
                    >
                        Tampilkan semua layanan
                    </a>
                </section>
            </div>
        @endforelse
    </div>
</section>

@if ($layanan->hasPages())
    <div class="mt-7">
        {{ $layanan->links() }}
    </div>
@endif
@endsection
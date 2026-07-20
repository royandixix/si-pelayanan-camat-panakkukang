@extends('layouts.masyarakat')

@section('judul', 'Daftar Layanan')
@section('deskripsi', 'Pilih layanan dan pelajari seluruh persyaratan sebelum membuat pengajuan.')

@section('breadcrumb')
    <a
        href="{{ route('masyarakat.dashboard') }}"
        class="text-slate-500 transition hover:text-slate-900"
    >
        Dashboard
    </a>
    <span class="text-slate-300">/</span>
    <span class="font-medium text-slate-900">Layanan</span>
@endsection

@section('judul_halaman', 'Daftar Layanan')
@section('deskripsi_halaman', 'Pilih layanan yang dibutuhkan dan baca seluruh standar pelayanan sebelum mengajukan permohonan.')

@section('konten')
    <div class="space-y-6">
        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <form
                method="GET"
                action="{{ route('masyarakat.layanan.index') }}"
                class="grid gap-4 lg:grid-cols-[1fr_280px_auto]"
            >
                <div>
                    <label
                        for="cari"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Cari Layanan
                    </label>

                    <input
                        id="cari"
                        name="cari"
                        type="search"
                        value="{{ $kataKunci }}"
                        placeholder="Nama, kode, atau deskripsi layanan"
                        class="min-h-11 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label
                        for="seksi"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Seksi atau Divisi
                    </label>

                    <select
                        id="seksi"
                        name="seksi"
                        class="min-h-11 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >
                        <option value="">Semua Seksi</option>

                        @foreach($daftarSeksi as $seksi)
                            <option
                                value="{{ $seksi->id }}"
                                @selected(
                                    (string) $seksiDipilih
                                    === (string) $seksi->id
                                )
                            >
                                {{ $seksi->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="inline-flex min-h-11 flex-1 items-center justify-center rounded-md bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"
                    >
                        Cari
                    </button>

                    @if($kataKunci !== '' || $seksiDipilih)
                        <a
                            href="{{ route('masyarakat.layanan.index') }}"
                            class="inline-flex min-h-11 items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </section>

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-950">
                    Layanan Tersedia
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Ditemukan {{ $layanan->total() }} layanan aktif.
                </p>
            </div>

            @if($layanan->total() > 0)
                <p class="text-sm text-slate-500">
                    Menampilkan {{ $layanan->firstItem() }}
                    sampai {{ $layanan->lastItem() }}
                </p>
            @endif
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse($layanan as $item)
                <article class="flex min-h-full flex-col rounded-lg border border-slate-200 bg-white shadow-sm transition hover:border-slate-300 hover:shadow-md">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <div class="flex items-start justify-between gap-3">
                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                {{ $item->code }}
                            </span>

                            @if($item->queue_enabled)
                                <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    Antrean Online
                                </span>
                            @endif
                        </div>

                        <h3 class="mt-4 text-lg font-bold leading-7 text-slate-950">
                            {{ $item->name }}
                        </h3>

                        <p class="mt-1 text-sm font-medium text-slate-500">
                            {{ $item->section?->name ?? 'Kantor Camat Panakkukang' }}
                        </p>
                    </div>

                    <div class="flex flex-1 flex-col px-5 py-5">
                        <p class="line-clamp-3 text-sm leading-6 text-slate-600">
                            {{ $item->description ?: 'Informasi pelayanan masyarakat Kantor Camat Panakkukang.' }}
                        </p>

                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded-md bg-slate-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Persyaratan
                                </p>
                                <p class="mt-1 text-sm font-bold text-slate-900">
                                    {{ $item->requirements_count }} dokumen
                                </p>
                            </div>

                            <div class="rounded-md bg-slate-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Waktu
                                </p>
                                <p class="mt-1 text-sm font-bold text-slate-900">
                                    {{ $item->processing_days ? $item->processing_days . ' hari' : 'Menyesuaikan' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-auto flex flex-col gap-2 pt-6 sm:flex-row">
                            <x-standar-layanan-modal :layanan="$item" />

                            <a
                                href="{{ route('masyarakat.layanan.show', [
                                    'layanan' => $item->slug,
                                ]) }}"
                                class="inline-flex min-h-11 items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                            >
                                Detail
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="md:col-span-2 xl:col-span-3">
                    <div class="rounded-lg border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                        <h3 class="text-base font-bold text-slate-900">
                            Layanan tidak ditemukan
                        </h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Ubah kata kunci atau pilih seksi yang berbeda.
                        </p>

                        <a
                            href="{{ route('masyarakat.layanan.index') }}"
                            class="mt-5 inline-flex min-h-11 items-center justify-center rounded-md bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"
                        >
                            Tampilkan Semua
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($layanan->hasPages())
            <div class="rounded-lg border border-slate-200 bg-white px-4 py-3 shadow-sm">
                {{ $layanan->links() }}
            </div>
        @endif
    </div>
@endsection
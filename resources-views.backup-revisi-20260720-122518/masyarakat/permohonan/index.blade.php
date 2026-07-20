@extends('layouts.masyarakat')

@section('judul', 'Permohonan Saya')
@section('deskripsi', 'Riwayat permohonan pelayanan masyarakat.')
@section('breadcrumb', 'Permohonan Saya')
@section('judul_halaman', 'Permohonan Saya')
@section('deskripsi_halaman', 'Pantau seluruh permohonan dan perkembangan pelayanan Anda.')

@section('aksi_header')
<a
    href="{{ route('masyarakat.layanan.index') }}"
    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-700"
>
    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 5v14"></path>
        <path d="M5 12h14"></path>
    </svg>
    Ajukan layanan
</a>
@endsection

@section('konten')
<section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
    <form method="GET" action="{{ route('masyarakat.permohonan.index') }}" class="grid gap-3 md:grid-cols-[1fr_240px_auto]">
        <div class="relative">
            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="11" cy="11" r="7"></circle>
                <path d="m20 20-3.5-3.5"></path>
            </svg>

            <input
                type="search"
                name="cari"
                value="{{ $cari }}"
                placeholder="Cari nomor atau nama layanan"
                class="block w-full rounded-lg border border-zinc-300 py-3 pl-12 pr-4 text-sm outline-none transition focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200"
            >
        </div>

        <select
            name="status"
            class="block w-full rounded-lg border border-zinc-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200"
        >
            <option value="">Semua status</option>
            @foreach ($statusPilihan as $nilai => $label)
                <option value="{{ $nilai }}" @selected($status === $nilai)>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="rounded-lg bg-zinc-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700">
            Terapkan
        </button>
    </form>

    @if ($cari !== '' || $status !== '')
        <div class="mt-4 flex items-center justify-between border-t border-zinc-100 pt-4">
            <p class="text-xs text-zinc-500">
                Ditemukan {{ $permohonan->total() }} permohonan
            </p>
            <a href="{{ route('masyarakat.permohonan.index') }}" class="text-xs font-semibold text-zinc-700 underline underline-offset-4">
                Hapus filter
            </a>
        </div>
    @endif
</section>

<div class="mt-6 space-y-4">
    @forelse ($permohonan as $item)
        @php
            $statusValue = $item->status instanceof \BackedEnum ? $item->status->value : (string) $item->status;
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
        @endphp

        <article class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-zinc-300 hover:shadow-md">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold text-zinc-500">
                            {{ $item->registration_number }}
                        </span>
                        <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $statusData[1] }}">
                            {{ $statusData[0] }}
                        </span>
                    </div>

                    <h2 class="mt-3 text-base font-semibold text-zinc-900">
                        {{ $item->service?->name ?? 'Layanan tidak tersedia' }}
                    </h2>

                    <p class="mt-1 text-xs text-zinc-500">
                        {{ $item->service?->section?->name ?? '-' }}
                    </p>

                    <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs text-zinc-500">
                        <span>
                            Diajukan {{ $item->submitted_at?->format('d M Y, H:i') ?? '-' }}
                        </span>
                        <span>
                            {{ $item->documents_count }} dokumen
                        </span>
                    </div>
                </div>

                <a
                    href="{{ route('masyarakat.permohonan.show', $item) }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-zinc-300 px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900 hover:bg-zinc-900 hover:text-white"
                >
                    Lihat detail
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14"></path>
                        <path d="m13 6 6 6-6 6"></path>
                    </svg>
                </a>
            </div>
        </article>
    @empty
        <section class="rounded-xl border border-dashed border-zinc-300 bg-white px-6 py-16 text-center">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-3-7 3V5a2 2 0 0 1 2-2Z"></path>
                </svg>
            </span>
            <h2 class="mt-4 text-sm font-semibold text-zinc-900">
                Belum ada permohonan
            </h2>
            <p class="mt-2 text-xs text-zinc-500">
                Pilih layanan untuk membuat permohonan pertama Anda.
            </p>
            <a href="{{ route('masyarakat.layanan.index') }}" class="mt-5 inline-flex rounded-lg bg-zinc-900 px-4 py-2.5 text-xs font-semibold text-white">
                Pilih layanan
            </a>
        </section>
    @endforelse
</div>

@if ($permohonan->hasPages())
    <div class="mt-7">
        {{ $permohonan->links() }}
    </div>
@endif
@endsection
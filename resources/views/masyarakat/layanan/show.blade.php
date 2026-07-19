@extends('layouts.masyarakat')

@section('judul', $layanan->name)
@section('deskripsi', $layanan->description ?: $layanan->name)
@section('breadcrumb', 'Detail Layanan')
@section('judul_halaman', $layanan->name)
@section('deskripsi_halaman', $layanan->section?->name ?? 'Pelayanan Masyarakat')

@section('aksi_header')
    <a href="{{ route('masyarakat.layanan.index') }}"
        class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5"></path>
            <path d="m11 18-6-6 6-6"></path>
        </svg>

        Kembali
    </a>
@endsection

@section('konten')
    <div class="grid gap-6 lg:grid-cols-[1fr_340px]">
        <div class="space-y-6">
            <section class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center gap-2">
                    @if ($layanan->code)
                        <span class="rounded-full bg-zinc-900 px-3 py-1 text-xs font-semibold text-white">
                            {{ $layanan->code }}
                        </span>
                    @endif

                    <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-600">
                        {{ $layanan->section?->name ?? 'Pelayanan' }}
                    </span>
                </div>

                <h2 class="mt-5 text-lg font-semibold text-zinc-900">
                    Tentang layanan
                </h2>

                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-zinc-600">
                    {{ $layanan->description ?: 'Belum ada deskripsi untuk layanan ini.' }}
                </p>
            </section>

            <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
                <header class="border-b border-zinc-200 px-6 py-5">
                    <h2 class="text-base font-semibold text-zinc-900">
                        Persyaratan dokumen
                    </h2>

                    <p class="mt-1 text-xs leading-5 text-zinc-500">
                        Siapkan seluruh dokumen sebelum mengirim permohonan.
                    </p>
                </header>

                <div class="divide-y divide-zinc-100">
                    @forelse ($layanan->requirements as $index => $syarat)
                        <div class="flex gap-4 px-6 py-5">
                            <span
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-zinc-100 text-xs font-semibold text-zinc-700">
                                {{ $index + 1 }}
                            </span>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-sm font-semibold text-zinc-900">
                                        {{ $syarat->name }}
                                    </h3>

                                    @if ($syarat->is_required)
                                        <span
                                            class="rounded-full bg-red-50 px-2.5 py-1 text-[10px] font-semibold text-red-600">
                                            Wajib
                                        </span>
                                    @else
                                        <span
                                            class="rounded-full bg-zinc-100 px-2.5 py-1 text-[10px] font-semibold text-zinc-500">
                                            Opsional
                                        </span>
                                    @endif
                                </div>

                                @if ($syarat->description)
                                    <p class="mt-2 text-xs leading-5 text-zinc-500">
                                        {{ $syarat->description }}
                                    </p>
                                @endif

                                <div class="mt-3 flex flex-wrap gap-2">
                                    @if (!empty($syarat->allowed_extensions))
                                        <span
                                            class="rounded bg-zinc-100 px-2.5 py-1 text-[10px] font-medium uppercase text-zinc-600">
                                            {{ implode(', ', $syarat->allowed_extensions) }}
                                        </span>
                                    @endif

                                    @if ($syarat->max_size_kb)
                                        <span class="rounded bg-zinc-100 px-2.5 py-1 text-[10px] font-medium text-zinc-600">
                                            Maksimal {{ number_format($syarat->max_size_kb / 1024, 1, ',', '.') }} MB
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <p class="text-sm font-medium text-zinc-700">
                                Tidak ada dokumen khusus
                            </p>

                            <p class="mt-2 text-xs text-zinc-500">
                                Layanan ini belum memiliki persyaratan dokumen.
                            </p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <aside>
            <div class="sticky top-24 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-zinc-900">
                    Ringkasan layanan
                </h2>

                <dl class="mt-5 divide-y divide-zinc-100">
                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-xs text-zinc-500">
                            Seksi
                        </dt>

                        <dd class="text-right text-xs font-semibold text-zinc-800">
                            {{ $layanan->section?->name ?? '-' }}
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-xs text-zinc-500">
                            Estimasi
                        </dt>

                        <dd class="text-right text-xs font-semibold text-zinc-800">
                            {{ $layanan->processing_days > 0 ? $layanan->processing_days . ' hari kerja' : 'Hari yang sama' }}
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-xs text-zinc-500">
                            Jumlah persyaratan
                        </dt>

                        <dd class="text-right text-xs font-semibold text-zinc-800">
                            {{ $layanan->requirements->count() }} dokumen
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-xs text-zinc-500">
                            Antrean
                        </dt>

                        <dd class="text-right text-xs font-semibold text-zinc-800">
                            {{ $layanan->queue_enabled ? 'Tersedia' : 'Tidak diperlukan' }}
                        </dd>
                    </div>
                </dl>

                <a href="{{ route('masyarakat.permohonan.create', $layanan->slug) }}"
                    class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700">
                    Ajukan layanan
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14"></path>
                        <path d="m13 6 6 6-6 6"></path>
                    </svg>
                </a>

                <p class="mt-3 text-center text-[11px] leading-5 text-zinc-400">
                    Pastikan seluruh data dan dokumen sudah disiapkan.
                </p>
            </div>
        </aside>
    </div>
@endsection

@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tombol = document.getElementById('tombol-ajukan-layanan');

            tombol?.addEventListener('click', function() {
                if (typeof window.Swal === 'undefined') {
                    alert('Form permohonan akan dibuat pada tahap berikutnya.');
                    return;
                }

                window.Swal.fire({
                    icon: 'info',
                    title: 'Form permohonan',
                    text: 'Halaman layanan sudah selesai. Selanjutnya kita membuat form permohonan untuk layanan ini.',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#18181b',
                });
            });
        });
    </script>
@endpush

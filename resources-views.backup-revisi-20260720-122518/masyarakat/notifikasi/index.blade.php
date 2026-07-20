@extends('layouts.masyarakat')

@section('judul', 'Notifikasi')
@section('deskripsi', 'Lihat seluruh informasi perkembangan pelayanan Anda.')
@section('breadcrumb', 'Notifikasi')
@section('judul_halaman', 'Notifikasi')
@section('deskripsi_halaman', 'Pantau perkembangan permohonan, dokumen, dan antrean pelayanan.')

@section('aksi_header')
@if ($jumlahBelumDibaca > 0)
    <form
        method="POST"
        action="{{ route('masyarakat.notifikasi.baca-semua') }}"
        data-form-baca-semua
    >
        @csrf
        @method('PATCH')

        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950"
        >
            <svg
                class="h-4 w-4"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path d="m5 12 4 4L19 6"></path>
                <path d="m5 18 2 2 12-12"></path>
            </svg>

            Tandai semua dibaca
        </button>
    </form>
@endif
@endsection

@section('konten')
<div class="space-y-6">
    <section class="rounded-xl border border-zinc-200 bg-white p-2 shadow-sm">
        <div class="grid gap-2 sm:grid-cols-3">
            <a
                href="{{ route('masyarakat.notifikasi.index', ['status' => 'semua']) }}"
                @class([
                    'flex items-center justify-between rounded-lg px-4 py-3 text-sm font-semibold transition',
                    'bg-zinc-900 text-white' => $status === 'semua',
                    'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' => $status !== 'semua',
                ])
            >
                <span>Semua</span>
                <span
                    @class([
                        'rounded-full px-2 py-0.5 text-xs',
                        'bg-white/15 text-white' => $status === 'semua',
                        'bg-zinc-100 text-zinc-600' => $status !== 'semua',
                    ])
                >
                    {{ $jumlahSemua }}
                </span>
            </a>

            <a
                href="{{ route('masyarakat.notifikasi.index', ['status' => 'belum-dibaca']) }}"
                @class([
                    'flex items-center justify-between rounded-lg px-4 py-3 text-sm font-semibold transition',
                    'bg-zinc-900 text-white' => $status === 'belum-dibaca',
                    'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' => $status !== 'belum-dibaca',
                ])
            >
                <span>Belum dibaca</span>
                <span
                    @class([
                        'rounded-full px-2 py-0.5 text-xs',
                        'bg-white/15 text-white' => $status === 'belum-dibaca',
                        'bg-red-50 text-red-600' => $status !== 'belum-dibaca' && $jumlahBelumDibaca > 0,
                        'bg-zinc-100 text-zinc-600' => $status !== 'belum-dibaca' && $jumlahBelumDibaca === 0,
                    ])
                >
                    {{ $jumlahBelumDibaca }}
                </span>
            </a>

            <a
                href="{{ route('masyarakat.notifikasi.index', ['status' => 'sudah-dibaca']) }}"
                @class([
                    'flex items-center justify-between rounded-lg px-4 py-3 text-sm font-semibold transition',
                    'bg-zinc-900 text-white' => $status === 'sudah-dibaca',
                    'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' => $status !== 'sudah-dibaca',
                ])
            >
                <span>Sudah dibaca</span>
                <span
                    @class([
                        'rounded-full px-2 py-0.5 text-xs',
                        'bg-white/15 text-white' => $status === 'sudah-dibaca',
                        'bg-zinc-100 text-zinc-600' => $status !== 'sudah-dibaca',
                    ])
                >
                    {{ $jumlahSudahDibaca }}
                </span>
            </a>
        </div>
    </section>

    @if ($notifikasi->isEmpty())
        <section class="rounded-xl border border-zinc-200 bg-white px-6 py-16 text-center shadow-sm">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-400">
                <svg
                    class="h-6 w-6"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z"></path>
                    <path d="M10 21h4"></path>
                </svg>
            </span>

            <h2 class="mt-4 text-base font-semibold text-zinc-900">
                Tidak ada notifikasi
            </h2>

            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-zinc-500">
                Informasi perkembangan permohonan, dokumen, dan antrean pelayanan akan muncul di halaman ini.
            </p>

            <a
                href="{{ route('masyarakat.dashboard') }}"
                class="mt-6 inline-flex items-center justify-center rounded-lg bg-zinc-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700"
            >
                Kembali ke dashboard
            </a>
        </section>
    @else
        <section class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
            <header class="flex flex-col gap-2 border-b border-zinc-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-zinc-900">
                        Daftar notifikasi
                    </h2>

                    <p class="mt-1 text-xs text-zinc-500">
                        Menampilkan {{ $notifikasi->firstItem() }}–{{ $notifikasi->lastItem() }} dari {{ $notifikasi->total() }} notifikasi.
                    </p>
                </div>

                @if ($jumlahBelumDibaca > 0)
                    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600">
                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                        {{ $jumlahBelumDibaca }} belum dibaca
                    </span>
                @endif
            </header>

            <div class="divide-y divide-zinc-100">
                @foreach ($notifikasi as $item)
                    @php
                        $data = $item->data;
                        $judul = data_get($data, 'judul', 'Informasi pelayanan');
                        $pesan = data_get($data, 'pesan', 'Terdapat informasi baru untuk Anda.');
                        $ikon = data_get($data, 'ikon', 'informasi');
                        $punyaTujuan = filled(data_get($data, 'url'));
                    @endphp

                    <article
                        @class([
                            'relative px-5 py-5 transition hover:bg-zinc-50',
                            'bg-blue-50/40' => $item->unread(),
                            'bg-white' => $item->read(),
                        ])
                    >
                        <div class="flex gap-4">
                            <span
                                @class([
                                    'flex h-11 w-11 shrink-0 items-center justify-center rounded-xl',
                                    'bg-blue-100 text-blue-700' => $ikon === 'informasi',
                                    'bg-amber-100 text-amber-700' => $ikon === 'peringatan',
                                    'bg-emerald-100 text-emerald-700' => $ikon === 'berhasil',
                                    'bg-red-100 text-red-700' => $ikon === 'ditolak',
                                    'bg-violet-100 text-violet-700' => $ikon === 'antrean',
                                ])
                            >
                                @if ($ikon === 'berhasil')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="m8 12 3 3 5-6"></path>
                                    </svg>
                                @elseif ($ikon === 'peringatan')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M12 3 2.5 20h19Z"></path>
                                        <path d="M12 9v4"></path>
                                        <path d="M12 17h.01"></path>
                                    </svg>
                                @elseif ($ikon === 'ditolak')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="m9 9 6 6"></path>
                                        <path d="m15 9-6 6"></path>
                                    </svg>
                                @elseif ($ikon === 'antrean')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M5 5h14"></path>
                                        <path d="M5 12h14"></path>
                                        <path d="M5 19h14"></path>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="M12 11v5"></path>
                                        <path d="M12 8h.01"></path>
                                    </svg>
                                @endif
                            </span>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <div class="flex min-w-0 items-center gap-2">
                                        <h3 class="text-sm font-semibold text-zinc-900">
                                            {{ $judul }}
                                        </h3>

                                        @if ($item->unread())
                                            <span class="h-2 w-2 shrink-0 rounded-full bg-blue-600"></span>
                                        @endif
                                    </div>

                                    <time class="shrink-0 text-xs text-zinc-400">
                                        {{ $item->created_at?->diffForHumans() }}
                                    </time>
                                </div>

                                <p class="mt-2 text-sm leading-6 text-zinc-600">
                                    {{ $pesan }}
                                </p>

                                <div class="mt-4 flex flex-wrap items-center gap-2">
                                    @if ($punyaTujuan)
                                        <form
                                            method="POST"
                                            action="{{ route('masyarakat.notifikasi.buka', $item->id) }}"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-zinc-700"
                                            >
                                                Lihat detail

                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="m9 18 6-6-6-6"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($item->unread())
                                        <form
                                            method="POST"
                                            action="{{ route('masyarakat.notifikasi.baca', $item->id) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-3.5 py-2 text-xs font-semibold text-zinc-600 transition hover:border-zinc-900 hover:text-zinc-950"
                                            >
                                                Tandai dibaca
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="m5 12 4 4L19 6"></path>
                                            </svg>

                                            Sudah dibaca
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($notifikasi->hasPages())
                <footer class="border-t border-zinc-200 px-5 py-4">
                    {{ $notifikasi->links() }}
                </footer>
            @endif
        </section>
    @endif
</div>
@endsection

@push('skrip')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formBacaSemua = document.querySelector('[data-form-baca-semua]');

        formBacaSemua?.addEventListener('submit', function (event) {
            event.preventDefault();

            function lanjutkan() {
                formBacaSemua.submit();
            }

            if (typeof window.Swal === 'undefined') {
                lanjutkan();
                return;
            }

            window.Swal.fire({
                icon: 'question',
                title: 'Tandai semua dibaca?',
                text: 'Seluruh notifikasi baru akan ditandai sebagai sudah dibaca.',
                showCancelButton: true,
                confirmButtonText: 'Ya, tandai',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                confirmButtonColor: '#18181b',
            }).then(function (hasil) {
                if (hasil.isConfirmed) {
                    lanjutkan();
                }
            });
        });
    });
</script>
@endpush
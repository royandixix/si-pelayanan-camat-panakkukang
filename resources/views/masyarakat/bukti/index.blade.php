@extends('layouts.masyarakat')

@section('judul', 'Bukti Permohonan')
@section('deskripsi', 'Tanda terima pengajuan pelayanan masyarakat.')
@section('breadcrumb', 'Bukti Permohonan')
@section('judul_halaman', 'Bukti Permohonan')
@section('deskripsi_halaman', 'Tanda terima resmi pengajuan pelayanan Anda.')

@section('aksi_header')
<div class="flex flex-wrap items-center gap-2">
    <a
        href="{{ route('masyarakat.permohonan.show', $permohonan) }}"
        class="inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950"
    >
        <svg
            class="h-4 w-4"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <path d="m15 18-6-6 6-6"></path>
        </svg>

        Kembali
    </a>

    <a
        href="{{ route('masyarakat.permohonan.bukti.download', $permohonan) }}"
        class="inline-flex items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-700"
    >
        <svg
            class="h-4 w-4"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <path d="M12 3v12"></path>
            <path d="m7 10 5 5 5-5"></path>
            <path d="M5 21h14"></path>
        </svg>

        Unduh PDF
    </a>
</div>
@endsection

@section('konten')
<style>
    @media print {
        nav,
        footer,
        header,
        [data-tidak-dicetak] {
            display: none !important;
        }

        body {
            background: white !important;
        }

        main {
            padding: 0 !important;
        }

        #lembar-bukti {
            border: 0 !important;
            box-shadow: none !important;
        }
    }
</style>

<div
    data-tidak-dicetak
    class="mb-5 flex justify-end"
>
    <button
        type="button"
        onclick="window.print()"
        class="inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-xs font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950"
    >
        <svg
            class="h-4 w-4"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <path d="M6 9V3h12v6"></path>
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
            <path d="M6 14h12v7H6Z"></path>
        </svg>

        Cetak bukti
    </button>
</div>

<article
    id="lembar-bukti"
    class="mx-auto max-w-4xl overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm"
>
    <div class="h-1.5 bg-gradient-to-r from-orange-500 via-red-500 to-fuchsia-500"></div>

    <header class="border-b border-zinc-200 px-6 py-7 sm:px-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-zinc-500">
                    Portal Pelayanan Masyarakat
                </p>

                <h1 class="mt-2 text-xl font-bold tracking-tight text-zinc-950">
                    Tanda Terima Permohonan
                </h1>

                <p class="mt-1 text-sm text-zinc-500">
                    Kecamatan Panakkukang
                </p>
            </div>

            <div class="rounded-xl bg-zinc-950 px-5 py-4 text-white sm:text-right">
                <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                    Nomor permohonan
                </p>

                <p class="mt-1 text-sm font-bold">
                    {{ $permohonan->registration_number }}
                </p>
            </div>
        </div>
    </header>

    <div class="grid gap-0 border-b border-zinc-200 sm:grid-cols-3">
        <div class="border-b border-zinc-200 px-6 py-5 sm:border-b-0 sm:border-r sm:px-8">
            <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                Status
            </p>

            <p class="mt-2 text-sm font-semibold text-zinc-900">
                {{ $statusLabel }}
            </p>
        </div>

        <div class="border-b border-zinc-200 px-6 py-5 sm:border-b-0 sm:border-r sm:px-8">
            <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                Tanggal pengajuan
            </p>

            <p class="mt-2 text-sm font-semibold text-zinc-900">
                {{ $tanggalPengajuan?->format('d M Y, H:i') ?? '-' }}
            </p>
        </div>

        <div class="px-6 py-5 sm:px-8">
            <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                Jumlah dokumen
            </p>

            <p class="mt-2 text-sm font-semibold text-zinc-900">
                {{ $daftarDokumen->count() }} dokumen
            </p>
        </div>
    </div>

    <div class="space-y-8 px-6 py-7 sm:px-8">
        <section>
            <h2 class="text-sm font-bold text-zinc-950">
                Identitas pemohon
            </h2>

            <div class="mt-4 grid gap-x-8 gap-y-5 sm:grid-cols-2">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                        Nama lengkap
                    </p>

                    <p class="mt-1 text-sm font-medium text-zinc-800">
                        {{ $pengguna->name }}
                    </p>
                </div>

                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                        NIK
                    </p>

                    <p class="mt-1 text-sm font-medium text-zinc-800">
                        {{ $pengguna->nik ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                        Email
                    </p>

                    <p class="mt-1 break-all text-sm font-medium text-zinc-800">
                        {{ $pengguna->email }}
                    </p>
                </div>

                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                        Nomor telepon
                    </p>

                    <p class="mt-1 text-sm font-medium text-zinc-800">
                        {{ $pengguna->phone ?: '-' }}
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                        Alamat
                    </p>

                    <p class="mt-1 text-sm leading-6 text-zinc-800">
                        {{ $pengguna->address ?: '-' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="border-t border-zinc-200 pt-7">
            <h2 class="text-sm font-bold text-zinc-950">
                Informasi pelayanan
            </h2>

            <div class="mt-4 grid gap-x-8 gap-y-5 sm:grid-cols-2">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                        Jenis layanan
                    </p>

                    <p class="mt-1 text-sm font-medium text-zinc-800">
                        {{ $permohonan->service?->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                        Seksi pelayanan
                    </p>

                    <p class="mt-1 text-sm font-medium text-zinc-800">
                        {{ $permohonan->service?->section?->name ?? '-' }}
                    </p>
                </div>

                @if ($permohonan->queue)
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                            Nomor antrean
                        </p>

                        <p class="mt-1 text-sm font-medium text-zinc-800">
                            {{ $permohonan->queue->queue_number }}
                        </p>
                    </div>
                @endif
            </div>
        </section>

        @if ($detailPengajuan->isNotEmpty())
            <section class="border-t border-zinc-200 pt-7">
                <h2 class="text-sm font-bold text-zinc-950">
                    Data pengajuan
                </h2>

                <div class="mt-4 grid gap-x-8 gap-y-5 sm:grid-cols-2">
                    @foreach ($detailPengajuan as $item)
                        <div class="{{ strlen($item['nilai']) > 80 ? 'sm:col-span-2' : '' }}">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                                {{ $item['label'] }}
                            </p>

                            <p class="mt-1 whitespace-pre-line text-sm leading-6 text-zinc-800">
                                {{ $item['nilai'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="border-t border-zinc-200 pt-7">
            <h2 class="text-sm font-bold text-zinc-950">
                Dokumen persyaratan
            </h2>

            <div class="mt-4 overflow-hidden rounded-xl border border-zinc-200">
                @forelse ($daftarDokumen as $dokumen)
                    <div class="flex flex-col gap-3 border-b border-zinc-100 px-4 py-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-zinc-900">
                                {{ $dokumen['nama'] }}
                            </p>

                            <p class="mt-1 truncate text-xs text-zinc-500">
                                {{ $dokumen['nama_file'] }}
                            </p>
                        </div>

                        <div class="flex shrink-0 items-center gap-3 text-xs">
                            <span class="text-zinc-400">
                                {{ $dokumen['ukuran'] }}
                            </span>

                            <span class="rounded-full bg-zinc-100 px-2.5 py-1 font-semibold text-zinc-600">
                                {{ $dokumen['status'] }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center text-xs text-zinc-500">
                        Tidak ada dokumen yang dilampirkan.
                    </div>
                @endforelse
            </div>
        </section>

        <section class="border-t border-zinc-200 pt-7">
            <div class="rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-5 text-center">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-zinc-400">
                    Kode verifikasi
                </p>

                <p class="mt-2 font-mono text-lg font-bold tracking-wider text-zinc-950">
                    {{ $kodeVerifikasi }}
                </p>

                <p class="mt-2 text-xs leading-5 text-zinc-500">
                    Simpan nomor permohonan dan kode ini sebagai bukti bahwa pengajuan telah tercatat di dalam sistem.
                </p>
            </div>
        </section>
    </div>

    <footer class="border-t border-zinc-200 bg-zinc-50 px-6 py-5 text-center sm:px-8">
        <p class="text-xs leading-5 text-zinc-500">
            Dokumen ini dibuat secara otomatis oleh Portal Pelayanan Masyarakat Kecamatan Panakkukang.
        </p>
    </footer>
</article>
@endsection
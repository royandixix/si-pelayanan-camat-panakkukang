@extends('layouts.masyarakat')

@section('judul', 'Buat Permohonan')
@section('deskripsi', 'Ajukan layanan ' . $layanan->name . '.')
@section('breadcrumb', 'Buat Permohonan')
@section('judul_halaman', 'Buat Permohonan')
@section('deskripsi_halaman', 'Lengkapi data dan dokumen untuk mengajukan layanan ' . $layanan->name . '.')

@section('aksi_header')
<a
    href="{{ route('masyarakat.layanan.show', $layanan->slug) }}"
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
<form
    id="form-permohonan"
    method="POST"
    action="{{ route('masyarakat.permohonan.store', $layanan->slug) }}"
    enctype="multipart/form-data"
>
    @csrf

    <div class="grid gap-6 lg:grid-cols-[1fr_340px]">
        <div class="space-y-6">
            <section class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-zinc-900 text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M6 3h9l3 3v15H6Z"></path>
                            <path d="M14 3v4h4"></path>
                            <path d="M9 12h6"></path>
                            <path d="M9 16h6"></path>
                        </svg>
                    </span>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-zinc-400">
                            {{ $layanan->section?->name }}
                        </p>
                        <h2 class="mt-1 text-lg font-semibold text-zinc-900">
                            {{ $layanan->name }}
                        </h2>
                        @if ($layanan->code)
                            <p class="mt-1 text-xs text-zinc-500">
                                Kode layanan: {{ $layanan->code }}
                            </p>
                        @endif
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
                <header class="border-b border-zinc-200 px-6 py-5">
                    <h2 class="text-base font-semibold text-zinc-900">
                        Data pemohon
                    </h2>
                    <p class="mt-1 text-xs leading-5 text-zinc-500">
                        Data diambil dari akun masyarakat yang sedang digunakan.
                    </p>
                </header>

                <div class="grid gap-5 p-6 sm:grid-cols-2">
                    <div>
                        <p class="text-xs text-zinc-500">Nama lengkap</p>
                        <p class="mt-1 text-sm font-semibold text-zinc-900">
                            {{ auth()->user()->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-zinc-500">NIK</p>
                        <p class="mt-1 text-sm font-semibold text-zinc-900">
                            {{ auth()->user()->nik }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-zinc-500">Email</p>
                        <p class="mt-1 break-all text-sm font-semibold text-zinc-900">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-zinc-500">Nomor telepon</p>
                        <p class="mt-1 text-sm font-semibold text-zinc-900">
                            {{ auth()->user()->phone }}
                        </p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-xs text-zinc-500">Alamat</p>
                        <p class="mt-1 text-sm font-semibold leading-6 text-zinc-900">
                            {{ auth()->user()->address }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-zinc-900">
                    Informasi permohonan
                </h2>

                <div class="mt-5">
                    <label for="keperluan" class="mb-2 block text-sm font-semibold text-zinc-800">
                        Keperluan permohonan
                        <span class="text-red-600">*</span>
                    </label>

                    <textarea
                        id="keperluan"
                        name="keperluan"
                        rows="5"
                        required
                        minlength="10"
                        maxlength="1000"
                        placeholder="Jelaskan keperluan pengajuan layanan ini"
                        @class([
                            'block w-full resize-none rounded-lg border bg-white px-4 py-3 text-sm leading-6 outline-none transition placeholder:text-zinc-400',
                            'border-red-500 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errors->has('keperluan'),
                            'border-zinc-300 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => !$errors->has('keperluan'),
                        ])
                    >{{ old('keperluan') }}</textarea>

                    @error('keperluan')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label for="catatan" class="mb-2 block text-sm font-semibold text-zinc-800">
                        Catatan tambahan
                    </label>

                    <textarea
                        id="catatan"
                        name="catatan"
                        rows="3"
                        maxlength="1000"
                        placeholder="Tambahkan informasi lain apabila diperlukan"
                        @class([
                            'block w-full resize-none rounded-lg border bg-white px-4 py-3 text-sm leading-6 outline-none transition placeholder:text-zinc-400',
                            'border-red-500 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errors->has('catatan'),
                            'border-zinc-300 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => !$errors->has('catatan'),
                        ])
                    >{{ old('catatan') }}</textarea>

                    @error('catatan')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </section>

            <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
                <header class="border-b border-zinc-200 px-6 py-5">
                    <h2 class="text-base font-semibold text-zinc-900">
                        Dokumen persyaratan
                    </h2>
                    <p class="mt-1 text-xs leading-5 text-zinc-500">
                        Unggah dokumen yang jelas dan dapat dibaca.
                    </p>
                </header>

                <div class="divide-y divide-zinc-100">
                    @forelse ($layanan->requirements as $syarat)
                        @php
                            $ekstensi = collect($syarat->allowed_extensions ?? [])
                                ->map(fn ($item) => strtolower(ltrim((string) $item, '.')))
                                ->filter()
                                ->values();
                            $accept = $ekstensi->map(fn ($item) => '.' . $item)->implode(',');
                        @endphp

                        <div class="p-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <label for="dokumen_{{ $syarat->id }}" class="text-sm font-semibold text-zinc-900">
                                            {{ $syarat->name }}
                                        </label>

                                        @if ($syarat->is_required)
                                            <span class="rounded-full bg-red-50 px-2.5 py-1 text-[10px] font-semibold text-red-600">
                                                Wajib
                                            </span>
                                        @else
                                            <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-[10px] font-semibold text-zinc-500">
                                                Opsional
                                            </span>
                                        @endif
                                    </div>

                                    @if ($syarat->description)
                                        <p class="mt-2 text-xs leading-5 text-zinc-500">
                                            {{ $syarat->description }}
                                        </p>
                                    @endif

                                    <p class="mt-2 text-[11px] text-zinc-400">
                                        @if ($ekstensi->isNotEmpty())
                                            Format: {{ $ekstensi->map(fn ($item) => strtoupper($item))->implode(', ') }}
                                        @endif
                                        @if ($syarat->max_size_kb)
                                            · Maksimal {{ number_format($syarat->max_size_kb / 1024, 1, ',', '.') }} MB
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <label
                                for="dokumen_{{ $syarat->id }}"
                                @class([
                                    'mt-4 flex cursor-pointer items-center gap-3 rounded-lg border border-dashed px-4 py-4 transition',
                                    'border-red-400 bg-red-50' => $errors->has('dokumen.' . $syarat->id),
                                    'border-zinc-300 hover:border-zinc-500 hover:bg-zinc-50' => !$errors->has('dokumen.' . $syarat->id),
                                ])
                            >
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M12 16V4"></path>
                                        <path d="m7 9 5-5 5 5"></path>
                                        <path d="M5 20h14"></path>
                                    </svg>
                                </span>

                                <span class="min-w-0">
                                    <span class="block text-sm font-medium text-zinc-700">
                                        Pilih dokumen
                                    </span>
                                    <span
                                        id="nama_dokumen_{{ $syarat->id }}"
                                        class="mt-1 block truncate text-xs text-zinc-400"
                                    >
                                        Belum ada berkas dipilih
                                    </span>
                                </span>

                                <input
                                    id="dokumen_{{ $syarat->id }}"
                                    type="file"
                                    name="dokumen[{{ $syarat->id }}]"
                                    accept="{{ $accept }}"
                                    @required($syarat->is_required)
                                    class="sr-only"
                                    data-file-input
                                    data-file-name="nama_dokumen_{{ $syarat->id }}"
                                >
                            </label>

                            @error('dokumen.' . $syarat->id)
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center">
                            <p class="text-sm font-medium text-zinc-800">
                                Tidak ada dokumen khusus
                            </p>
                            <p class="mt-2 text-xs text-zinc-500">
                                Anda dapat langsung mengirim permohonan.
                            </p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <aside>
            <div class="sticky top-24 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-zinc-900">
                    Ringkasan pengajuan
                </h2>

                <dl class="mt-5 divide-y divide-zinc-100">
                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-xs text-zinc-500">Layanan</dt>
                        <dd class="max-w-44 text-right text-xs font-semibold text-zinc-800">
                            {{ $layanan->name }}
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-xs text-zinc-500">Estimasi</dt>
                        <dd class="text-right text-xs font-semibold text-zinc-800">
                            {{ $layanan->processing_days ? $layanan->processing_days . ' hari kerja' : 'Hari yang sama' }}
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-xs text-zinc-500">Persyaratan</dt>
                        <dd class="text-right text-xs font-semibold text-zinc-800">
                            {{ $layanan->requirements->count() }} dokumen
                        </dd>
                    </div>
                </dl>

                <label class="mt-5 flex cursor-pointer items-start gap-3 rounded-lg border border-zinc-200 bg-zinc-50 p-4">
                    <input
                        type="checkbox"
                        name="konfirmasi"
                        value="1"
                        required
                        @checked(old('konfirmasi'))
                        class="mt-0.5 h-4 w-4 shrink-0 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-500"
                    >
                    <span class="text-xs leading-5 text-zinc-600">
                        Saya menyatakan bahwa seluruh data dan dokumen yang dikirim benar.
                    </span>
                </label>

                @error('konfirmasi')
                    <p class="mt-2 text-xs font-medium text-red-600">
                        {{ $message }}
                    </p>
                @enderror

                <button
                    id="tombol-kirim"
                    type="submit"
                    class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:bg-zinc-300"
                >
                    <svg id="spinner-kirim" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"></path>
                    </svg>
                    <span id="teks-kirim">Kirim permohonan</span>
                </button>
            </div>
        </aside>
    </div>
</form>
@endsection

@push('skrip')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-file-input]').forEach(function (input) {
            input.addEventListener('change', function () {
                const target = document.getElementById(input.dataset.fileName);

                if (!target) {
                    return;
                }

                target.textContent = input.files.length
                    ? input.files[0].name
                    : 'Belum ada berkas dipilih';

                target.classList.toggle('text-emerald-600', input.files.length > 0);
                target.classList.toggle('text-zinc-400', input.files.length === 0);
            });
        });

        const form = document.getElementById('form-permohonan');
        const tombol = document.getElementById('tombol-kirim');
        const spinner = document.getElementById('spinner-kirim');
        const teks = document.getElementById('teks-kirim');

        function kirimForm() {
            form.dataset.sedangKirim = '1';
            tombol.disabled = true;
            spinner.classList.remove('hidden');
            teks.textContent = 'Mengirim...';
            form.submit();
        }

        form?.addEventListener('submit', function (event) {
            if (form.dataset.sedangKirim === '1') {
                return;
            }

            event.preventDefault();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (typeof window.Swal === 'undefined') {
                kirimForm();
                return;
            }

            window.Swal.fire({
                icon: 'question',
                title: 'Kirim permohonan?',
                text: 'Pastikan seluruh data dan dokumen sudah benar.',
                showCancelButton: true,
                confirmButtonText: 'Ya, kirim',
                cancelButtonText: 'Periksa lagi',
                confirmButtonColor: '#18181b',
                cancelButtonColor: '#71717a',
                reverseButtons: true,
            }).then(function (hasil) {
                if (hasil.isConfirmed) {
                    kirimForm();
                }
            });
        });
    });
</script>
@endpush
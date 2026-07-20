@extends('layouts.masyarakat')

@section('judul', 'Ubah Kata Sandi')
@section('deskripsi', 'Perbarui kata sandi akun masyarakat.')
@section('breadcrumb', 'Profil / Keamanan')
@section('judul_halaman', 'Ubah Kata Sandi')
@section('deskripsi_halaman', 'Gunakan kata sandi yang kuat untuk menjaga keamanan akun Anda.')

@section('aksi_header')
<a
    href="{{ route('masyarakat.profil.index') }}"
    class="inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950"
>
    <svg
        class="h-4 w-4"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
    >
        <path d="M19 12H5"></path>
        <path d="m11 18-6-6 6-6"></path>
    </svg>

    Kembali ke profil
</a>
@endsection

@section('konten')
<div class="mx-auto grid max-w-5xl gap-6 lg:grid-cols-[1fr_320px]">
    <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
        <header class="border-b border-zinc-200 px-6 py-5">
            <p class="text-[11px] font-medium uppercase tracking-wider text-zinc-400">
                Keamanan akun
            </p>

            <h2 class="mt-1 text-base font-semibold text-zinc-900">
                Ganti kata sandi
            </h2>

            <p class="mt-1 text-xs leading-5 text-zinc-500">
                Masukkan kata sandi saat ini sebelum menentukan kata sandi baru.
            </p>
        </header>

        <form
            id="form-kata-sandi"
            method="POST"
            action="{{ route('masyarakat.profil.kata-sandi.update') }}"
            class="p-6"
        >
            @csrf
            @method('PATCH')

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4" role="alert">
                    <div class="flex items-start gap-3">
                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 8v5"></path>
                            <path d="M12 16h.01"></path>
                        </svg>

                        <div>
                            <p class="text-sm font-semibold text-red-800">
                                Kata sandi belum dapat diperbarui
                            </p>

                            <p class="mt-1 text-xs leading-5 text-red-700">
                                Periksa kembali kata sandi yang Anda masukkan.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-5">
                <div>
                    <label
                        for="current_password"
                        class="mb-2 block text-sm font-semibold text-zinc-800"
                    >
                        Kata sandi saat ini
                        <span class="text-red-600">*</span>
                    </label>

                    <div class="relative">
                        <input
                            id="current_password"
                            type="password"
                            name="current_password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi saat ini"
                            @class([
                                'block w-full rounded-lg border bg-white px-4 py-3 pr-12 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400',
                                'border-red-400 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errors->has('current_password'),
                                'border-zinc-300 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => !$errors->has('current_password'),
                            ])
                        >

                        <button
                            type="button"
                            data-toggle-password="current_password"
                            class="absolute right-1 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-lg text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900"
                            aria-label="Tampilkan kata sandi saat ini"
                        >
                            <svg
                                data-eye-open
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>

                            <svg
                                data-eye-closed
                                class="hidden h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="m3 3 18 18"></path>
                                <path d="M10.6 10.7a2 2 0 0 0 2.7 2.7"></path>
                                <path d="M9.9 4.2A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a17.5 17.5 0 0 1-2.1 3.2"></path>
                                <path d="M6.6 6.6C3.6 8.5 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 4.2-.9"></path>
                            </svg>
                        </button>
                    </div>

                    @error('current_password')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="password"
                        class="mb-2 block text-sm font-semibold text-zinc-800"
                    >
                        Kata sandi baru
                        <span class="text-red-600">*</span>
                    </label>

                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            placeholder="Masukkan kata sandi baru"
                            @class([
                                'block w-full rounded-lg border bg-white px-4 py-3 pr-12 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400',
                                'border-red-400 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errors->has('password'),
                                'border-zinc-300 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => !$errors->has('password'),
                            ])
                        >

                        <button
                            type="button"
                            data-toggle-password="password"
                            class="absolute right-1 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-lg text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900"
                            aria-label="Tampilkan kata sandi baru"
                        >
                            <svg
                                data-eye-open
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>

                            <svg
                                data-eye-closed
                                class="hidden h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="m3 3 18 18"></path>
                                <path d="M10.6 10.7a2 2 0 0 0 2.7 2.7"></path>
                                <path d="M9.9 4.2A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a17.5 17.5 0 0 1-2.1 3.2"></path>
                                <path d="M6.6 6.6C3.6 8.5 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 4.2-.9"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-3">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-zinc-500">
                                Kekuatan kata sandi
                            </p>

                            <p
                                id="label-kekuatan"
                                class="text-xs font-semibold text-zinc-500"
                            >
                                Belum diisi
                            </p>
                        </div>

                        <div class="mt-2 grid grid-cols-4 gap-1.5">
                            <span data-strength-bar class="h-1.5 rounded-full bg-zinc-200"></span>
                            <span data-strength-bar class="h-1.5 rounded-full bg-zinc-200"></span>
                            <span data-strength-bar class="h-1.5 rounded-full bg-zinc-200"></span>
                            <span data-strength-bar class="h-1.5 rounded-full bg-zinc-200"></span>
                        </div>
                    </div>

                    @error('password')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="password_confirmation"
                        class="mb-2 block text-sm font-semibold text-zinc-800"
                    >
                        Konfirmasi kata sandi baru
                        <span class="text-red-600">*</span>
                    </label>

                    <div class="relative">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            placeholder="Ulangi kata sandi baru"
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-4 py-3 pr-12 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200"
                        >

                        <button
                            type="button"
                            data-toggle-password="password_confirmation"
                            class="absolute right-1 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-lg text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900"
                            aria-label="Tampilkan konfirmasi kata sandi"
                        >
                            <svg
                                data-eye-open
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>

                            <svg
                                data-eye-closed
                                class="hidden h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="m3 3 18 18"></path>
                                <path d="M10.6 10.7a2 2 0 0 0 2.7 2.7"></path>
                                <path d="M9.9 4.2A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a17.5 17.5 0 0 1-2.1 3.2"></path>
                                <path d="M6.6 6.6C3.6 8.5 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 4.2-.9"></path>
                            </svg>
                        </button>
                    </div>

                    <p
                        id="status-konfirmasi"
                        class="mt-2 hidden text-xs font-medium"
                    ></p>
                </div>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 border-t border-zinc-200 pt-6 sm:flex-row sm:items-center sm:justify-end">
                <a
                    href="{{ route('masyarakat.profil.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950"
                >
                    Batal
                </a>

                <button
                    id="tombol-simpan-kata-sandi"
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-zinc-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:bg-zinc-400"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                        <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                    </svg>

                    <span>Perbarui kata sandi</span>
                </button>
            </div>
        </form>
    </section>

    <aside class="space-y-6">
        <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
            <h2 class="text-sm font-semibold text-zinc-900">
                Persyaratan kata sandi
            </h2>

            <div class="mt-4 space-y-3">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-zinc-500">
                        <svg
                            class="h-3 w-3"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="m8 12 2.5 2.5L16 9"></path>
                        </svg>
                    </span>

                    <p class="text-xs leading-5 text-zinc-600">
                        Minimal terdiri dari 8 karakter.
                    </p>
                </div>

                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-zinc-500">
                        <svg
                            class="h-3 w-3"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="m8 12 2.5 2.5L16 9"></path>
                        </svg>
                    </span>

                    <p class="text-xs leading-5 text-zinc-600">
                        Mengandung huruf besar dan huruf kecil.
                    </p>
                </div>

                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-zinc-500">
                        <svg
                            class="h-3 w-3"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="m8 12 2.5 2.5L16 9"></path>
                        </svg>
                    </span>

                    <p class="text-xs leading-5 text-zinc-600">
                        Mengandung setidaknya satu angka.
                    </p>
                </div>

                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-zinc-500">
                        <svg
                            class="h-3 w-3"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="m8 12 2.5 2.5L16 9"></path>
                        </svg>
                    </span>

                    <p class="text-xs leading-5 text-zinc-600">
                        Tidak menggunakan data pribadi yang mudah ditebak.
                    </p>
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-amber-200 bg-amber-50 p-5">
            <div class="flex gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                    <svg
                        class="h-4 w-4"
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
                        Jaga kerahasiaan akun
                    </h2>

                    <p class="mt-2 text-xs leading-5 text-amber-800">
                        Jangan memberikan kata sandi atau kode masuk akun kepada orang lain, termasuk pihak yang mengaku sebagai petugas.
                    </p>
                </div>
            </div>
        </section>
    </aside>
</div>
@endsection

@push('skrip')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-kata-sandi');
        const kataSandiBaru = document.getElementById('password');
        const konfirmasi = document.getElementById('password_confirmation');
        const statusKonfirmasi = document.getElementById('status-konfirmasi');
        const labelKekuatan = document.getElementById('label-kekuatan');
        const batangKekuatan = document.querySelectorAll('[data-strength-bar]');
        const tombolSimpan = document.getElementById('tombol-simpan-kata-sandi');

        document.querySelectorAll('[data-toggle-password]').forEach(function (tombol) {
            tombol.addEventListener('click', function () {
                const input = document.getElementById(tombol.dataset.togglePassword);
                const mataTerbuka = tombol.querySelector('[data-eye-open]');
                const mataTertutup = tombol.querySelector('[data-eye-closed]');
                const sedangTampil = input.type === 'text';

                input.type = sedangTampil ? 'password' : 'text';
                mataTerbuka.classList.toggle('hidden', !sedangTampil);
                mataTertutup.classList.toggle('hidden', sedangTampil);
            });
        });

        function hitungKekuatan(nilai) {
            let skor = 0;

            if (nilai.length >= 8) skor++;
            if (/[a-z]/.test(nilai) && /[A-Z]/.test(nilai)) skor++;
            if (/[0-9]/.test(nilai)) skor++;
            if (/[^A-Za-z0-9]/.test(nilai) || nilai.length >= 12) skor++;

            return skor;
        }

        function perbaruiKekuatan() {
            const nilai = kataSandiBaru.value;
            const skor = hitungKekuatan(nilai);

            let label = 'Belum diisi';
            let kelasBatang = 'bg-zinc-200';
            let kelasLabel = 'text-zinc-500';

            if (nilai.length > 0 && skor <= 1) {
                label = 'Lemah';
                kelasBatang = 'bg-red-500';
                kelasLabel = 'text-red-600';
            } else if (skor === 2) {
                label = 'Cukup';
                kelasBatang = 'bg-amber-500';
                kelasLabel = 'text-amber-600';
            } else if (skor === 3) {
                label = 'Kuat';
                kelasBatang = 'bg-blue-500';
                kelasLabel = 'text-blue-600';
            } else if (skor === 4) {
                label = 'Sangat kuat';
                kelasBatang = 'bg-emerald-500';
                kelasLabel = 'text-emerald-600';
            }

            labelKekuatan.textContent = label;
            labelKekuatan.className = `text-xs font-semibold ${kelasLabel}`;

            batangKekuatan.forEach(function (batang, indeks) {
                batang.className = 'h-1.5 rounded-full bg-zinc-200';

                if (indeks < skor) {
                    batang.className = `h-1.5 rounded-full ${kelasBatang}`;
                }
            });
        }

        function perbaruiKonfirmasi() {
            if (!konfirmasi.value) {
                statusKonfirmasi.classList.add('hidden');
                return;
            }

            statusKonfirmasi.classList.remove('hidden');

            if (kataSandiBaru.value === konfirmasi.value) {
                statusKonfirmasi.textContent = 'Konfirmasi kata sandi sesuai.';
                statusKonfirmasi.className = 'mt-2 text-xs font-medium text-emerald-600';
                return;
            }

            statusKonfirmasi.textContent = 'Konfirmasi kata sandi belum sesuai.';
            statusKonfirmasi.className = 'mt-2 text-xs font-medium text-red-600';
        }

        kataSandiBaru.addEventListener('input', function () {
            perbaruiKekuatan();
            perbaruiKonfirmasi();
        });

        konfirmasi.addEventListener('input', perbaruiKonfirmasi);

        perbaruiKekuatan();
        perbaruiKonfirmasi();

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (!form.reportValidity()) {
                return;
            }

            if (kataSandiBaru.value !== konfirmasi.value) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Konfirmasi tidak sesuai',
                        text: 'Kata sandi baru dan konfirmasinya harus sama.',
                        confirmButtonText: 'Periksa kembali',
                        confirmButtonColor: '#18181b',
                    });
                }

                konfirmasi.focus();
                return;
            }

            if (typeof Swal === 'undefined') {
                tombolSimpan.disabled = true;
                form.submit();
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Perbarui kata sandi?',
                text: 'Gunakan kata sandi baru saat masuk kembali ke akun.',
                showCancelButton: true,
                confirmButtonText: 'Ya, perbarui',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                confirmButtonColor: '#18181b',
            }).then(function (hasil) {
                if (!hasil.isConfirmed) {
                    return;
                }

                tombolSimpan.disabled = true;
                tombolSimpan.querySelector('span').textContent = 'Memperbarui...';
                form.submit();
            });
        });
    });
</script>
@endpush
@extends('layouts.autentikasi')

@section('judul', 'Daftar Masyarakat')

@section(
    'deskripsi',
    'Daftar akun masyarakat Kecamatan Panakkukang.'
)

@section('lebar', 'max-w-lg')

@php
    $errorRegister = $errors->getBag('register');

    if (! $errorRegister->any()) {
        $errorRegister = $errors->getBag('default');
    }
@endphp

@section('konten')
<section class="border border-zinc-200 bg-white shadow-[0_14px_35px_rgba(24,24,27,0.16)]">
    <div class="p-6 sm:p-8">
        <header class="border-b border-zinc-200 pb-5">
            <h2 class="text-2xl font-semibold tracking-tight text-zinc-800">
                Daftar
            </h2>

            <p class="mt-2 text-sm leading-6 text-zinc-500">
                Buat akun untuk mengakses layanan masyarakat.
            </p>
        </header>

        <form
            id="form-register"
            method="POST"
            action="{{ route('register.store') }}"
            class="mt-6 space-y-5"
        >
            @csrf

            <div>
                <label
                    for="register_name"
                    class="mb-2 block text-sm font-semibold text-zinc-800"
                >
                    Nama lengkap
                    <span class="text-red-600">*</span>
                </label>

                <input
                    id="register_name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    minlength="3"
                    maxlength="255"
                    placeholder="Nama sesuai identitas"
                    @class([
                        'block w-full border bg-white px-3.5 py-3 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400',
                        'border-red-500 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errorRegister->has('name'),
                        'border-zinc-400 hover:border-zinc-600 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => ! $errorRegister->has('name'),
                    ])
                >

                @if ($errorRegister->has('name'))
                    <p class="mt-2 text-xs text-red-600">
                        {{ $errorRegister->first('name') }}
                    </p>
                @endif
            </div>

            <div>
                <div class="mb-2 flex items-center justify-between gap-4">
                    <label
                        for="register_nik"
                        class="block text-sm font-semibold text-zinc-800"
                    >
                        NIK
                        <span class="text-red-600">*</span>
                    </label>

                    <span
                        id="penghitung-nik"
                        class="text-xs font-medium text-zinc-400"
                    >
                        {{ strlen(old('nik', '')) }}/16
                    </span>
                </div>

                <input
                    id="register_nik"
                    type="text"
                    name="nik"
                    value="{{ old('nik') }}"
                    required
                    inputmode="numeric"
                    minlength="16"
                    maxlength="16"
                    placeholder="Masukkan 16 digit NIK"
                    @class([
                        'block w-full border bg-white px-3.5 py-3 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400',
                        'border-red-500 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errorRegister->has('nik'),
                        'border-zinc-400 hover:border-zinc-600 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => ! $errorRegister->has('nik'),
                    ])
                >

                @if ($errorRegister->has('nik'))
                    <p class="mt-2 text-xs text-red-600">
                        {{ $errorRegister->first('nik') }}
                    </p>
                @else
                    <p class="mt-2 text-xs leading-5 text-zinc-500">
                        NIK digunakan untuk identifikasi pelayanan.
                    </p>
                @endif
            </div>

            <div>
                <label
                    for="register_phone"
                    class="mb-2 block text-sm font-semibold text-zinc-800"
                >
                    Nomor telepon
                    <span class="text-red-600">*</span>
                </label>

                <input
                    id="register_phone"
                    type="tel"
                    name="phone"
                    value="{{ old('phone') }}"
                    required
                    autocomplete="tel"
                    maxlength="20"
                    placeholder="081234567890"
                    @class([
                        'block w-full border bg-white px-3.5 py-3 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400',
                        'border-red-500 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errorRegister->has('phone'),
                        'border-zinc-400 hover:border-zinc-600 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => ! $errorRegister->has('phone'),
                    ])
                >

                @if ($errorRegister->has('phone'))
                    <p class="mt-2 text-xs text-red-600">
                        {{ $errorRegister->first('phone') }}
                    </p>
                @endif
            </div>

            <div>
                <label
                    for="register_email"
                    class="mb-2 block text-sm font-semibold text-zinc-800"
                >
                    Alamat email
                    <span class="text-red-600">*</span>
                </label>

                <input
                    id="register_email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    maxlength="255"
                    placeholder="nama@email.com"
                    @class([
                        'block w-full border bg-white px-3.5 py-3 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400',
                        'border-red-500 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errorRegister->has('email'),
                        'border-zinc-400 hover:border-zinc-600 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => ! $errorRegister->has('email'),
                    ])
                >

                @if ($errorRegister->has('email'))
                    <p class="mt-2 text-xs text-red-600">
                        {{ $errorRegister->first('email') }}
                    </p>
                @else
                    <p class="mt-2 text-xs leading-5 text-zinc-500">
                        Email digunakan untuk masuk ke sistem.
                    </p>
                @endif
            </div>

            <div>
                <label
                    for="register_address"
                    class="mb-2 block text-sm font-semibold text-zinc-800"
                >
                    Alamat lengkap
                    <span class="text-red-600">*</span>
                </label>

                <textarea
                    id="register_address"
                    name="address"
                    rows="3"
                    required
                    minlength="10"
                    maxlength="1000"
                    placeholder="Masukkan alamat lengkap tempat tinggal"
                    @class([
                        'block w-full resize-none border bg-white px-3.5 py-3 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400',
                        'border-red-500 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errorRegister->has('address'),
                        'border-zinc-400 hover:border-zinc-600 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => ! $errorRegister->has('address'),
                    ])
                >{{ old('address') }}</textarea>

                @if ($errorRegister->has('address'))
                    <p class="mt-2 text-xs text-red-600">
                        {{ $errorRegister->first('address') }}
                    </p>
                @endif
            </div>

            <div>
                <label
                    for="register_password"
                    class="mb-2 block text-sm font-semibold text-zinc-800"
                >
                    Kata sandi
                    <span class="text-red-600">*</span>
                </label>

                <div
                    @class([
                        'flex overflow-hidden border bg-white transition focus-within:ring-2',
                        'border-red-500 focus-within:border-red-600 focus-within:ring-red-100' => $errorRegister->has('password'),
                        'border-zinc-400 hover:border-zinc-600 focus-within:border-zinc-900 focus-within:ring-zinc-200' => ! $errorRegister->has('password'),
                    ])
                >
                    <input
                        id="register_password"
                        type="password"
                        name="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        placeholder="Masukkan kata sandi"
                        class="min-w-0 flex-1 border-0 bg-transparent px-3.5 py-3 text-sm text-zinc-900 outline-none placeholder:text-zinc-400 focus:ring-0"
                    >

                    <button
                        id="tombol-password-register"
                        type="button"
                        class="shrink-0 border-l border-zinc-300 px-4 text-sm font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-950"
                        aria-controls="register_password"
                    >
                        Tampilkan
                    </button>
                </div>

                @if ($errorRegister->has('password'))
                    <p class="mt-2 text-xs text-red-600">
                        {{ $errorRegister->first('password') }}
                    </p>
                @endif

                <div class="mt-3 space-y-1.5 text-xs">
                    <p
                        id="aturan-panjang"
                        class="flex items-center gap-2 text-zinc-500"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-zinc-300"></span>
                        Minimal 8 karakter
                    </p>

                    <p
                        id="aturan-besar"
                        class="flex items-center gap-2 text-zinc-500"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-zinc-300"></span>
                        Memiliki huruf besar
                    </p>

                    <p
                        id="aturan-kecil"
                        class="flex items-center gap-2 text-zinc-500"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-zinc-300"></span>
                        Memiliki huruf kecil
                    </p>

                    <p
                        id="aturan-angka-simbol"
                        class="flex items-center gap-2 text-zinc-500"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-zinc-300"></span>
                        Memiliki angka dan simbol
                    </p>
                </div>
            </div>

            <div>
                <label
                    for="register_password_confirmation"
                    class="mb-2 block text-sm font-semibold text-zinc-800"
                >
                    Konfirmasi kata sandi
                    <span class="text-red-600">*</span>
                </label>

                <div
                    id="pembungkus-konfirmasi"
                    class="flex overflow-hidden border border-zinc-400 bg-white transition hover:border-zinc-600 focus-within:border-zinc-900 focus-within:ring-2 focus-within:ring-zinc-200"
                >
                    <input
                        id="register_password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi kata sandi"
                        class="min-w-0 flex-1 border-0 bg-transparent px-3.5 py-3 text-sm text-zinc-900 outline-none placeholder:text-zinc-400 focus:ring-0"
                    >

                    <button
                        id="tombol-konfirmasi-register"
                        type="button"
                        class="shrink-0 border-l border-zinc-300 px-4 text-sm font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-950"
                        aria-controls="register_password_confirmation"
                    >
                        Tampilkan
                    </button>
                </div>

                <p
                    id="pesan-konfirmasi"
                    class="mt-2 hidden text-xs"
                ></p>
            </div>

            <div>
                <label class="flex cursor-pointer items-start gap-3">
                    <input
                        id="register_agreement"
                        type="checkbox"
                        name="agreement"
                        value="1"
                        required
                        @checked(old('agreement'))
                        class="mt-0.5 h-4 w-4 shrink-0 border-zinc-400 text-zinc-900 focus:ring-zinc-500"
                    >

                    <span class="text-xs leading-5 text-zinc-700">
                        Saya menyatakan bahwa data yang dimasukkan benar dan
                        dapat digunakan untuk proses pelayanan administrasi
                        Kecamatan Panakkukang.
                    </span>
                </label>

                @if ($errorRegister->has('agreement'))
                    <p class="mt-2 text-xs text-red-600">
                        {{ $errorRegister->first('agreement') }}
                    </p>
                @endif
            </div>

            <button
                id="tombol-register"
                type="submit"
                disabled
                class="inline-flex w-full items-center justify-center gap-2 bg-zinc-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-zinc-200 disabled:text-zinc-400"
            >
                <svg
                    id="spinner-register"
                    class="hidden h-4 w-4 animate-spin"
                    viewBox="0 0 24 24"
                    fill="none"
                    aria-hidden="true"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"
                    ></path>
                </svg>

                <span id="teks-tombol-register">
                    Buat akun
                </span>
            </button>
        </form>
    </div>
</section>

<div class="mt-7 text-center">
    <p class="text-sm text-zinc-700">
        Sudah memiliki akun?
    </p>

    <a
        href="{{ route('login') }}"
        class="mt-3 inline-block text-sm font-semibold text-zinc-800 underline decoration-zinc-400 underline-offset-4 transition hover:text-black"
    >
        Masuk
    </a>
</div>
@endsection

@push('skrip')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById(
            'form-register'
        );

        const inputNik = document.getElementById(
            'register_nik'
        );

        const penghitungNik = document.getElementById(
            'penghitung-nik'
        );

        const password = document.getElementById(
            'register_password'
        );

        const konfirmasi = document.getElementById(
            'register_password_confirmation'
        );

        const pembungkusKonfirmasi = document.getElementById(
            'pembungkus-konfirmasi'
        );

        const persetujuan = document.getElementById(
            'register_agreement'
        );

        const tombolSubmit = document.getElementById(
            'tombol-register'
        );

        const teksSubmit = document.getElementById(
            'teks-tombol-register'
        );

        const spinner = document.getElementById(
            'spinner-register'
        );

        const pesanKonfirmasi = document.getElementById(
            'pesan-konfirmasi'
        );

        function aktifkanTombolPassword(
            idTombol,
            input
        ) {
            const tombol = document.getElementById(idTombol);

            tombol?.addEventListener('click', function () {
                const terlihat = input.type === 'text';

                input.type = terlihat
                    ? 'password'
                    : 'text';

                tombol.textContent = terlihat
                    ? 'Tampilkan'
                    : 'Sembunyikan';

                tombol.setAttribute(
                    'aria-label',
                    terlihat
                        ? 'Tampilkan kata sandi'
                        : 'Sembunyikan kata sandi'
                );

                input.focus();
            });
        }

        aktifkanTombolPassword(
            'tombol-password-register',
            password
        );

        aktifkanTombolPassword(
            'tombol-konfirmasi-register',
            konfirmasi
        );

        function perbaruiNik() {
            inputNik.value = inputNik.value
                .replace(/\D/g, '')
                .slice(0, 16);

            const jumlah = inputNik.value.length;

            penghitungNik.textContent = `${jumlah}/16`;

            penghitungNik.classList.toggle(
                'text-emerald-600',
                jumlah === 16
            );

            penghitungNik.classList.toggle(
                'text-zinc-400',
                jumlah !== 16
            );
        }

        function ubahAturan(id, valid) {
            const elemen = document.getElementById(id);

            if (!elemen) {
                return;
            }

            elemen.classList.toggle(
                'text-emerald-600',
                valid
            );

            elemen.classList.toggle(
                'text-zinc-500',
                !valid
            );

            const titik = elemen.querySelector('span');

            titik?.classList.toggle(
                'bg-emerald-500',
                valid
            );

            titik?.classList.toggle(
                'bg-zinc-300',
                !valid
            );
        }

        function passwordValid() {
            const nilai = password.value;

            return nilai.length >= 8
                && /[A-Z]/.test(nilai)
                && /[a-z]/.test(nilai)
                && /[0-9]/.test(nilai)
                && /[^A-Za-z0-9]/.test(nilai);
        }

        function perbaruiPassword() {
            const nilai = password.value;

            ubahAturan(
                'aturan-panjang',
                nilai.length >= 8
            );

            ubahAturan(
                'aturan-besar',
                /[A-Z]/.test(nilai)
            );

            ubahAturan(
                'aturan-kecil',
                /[a-z]/.test(nilai)
            );

            ubahAturan(
                'aturan-angka-simbol',
                /[0-9]/.test(nilai)
                    && /[^A-Za-z0-9]/.test(nilai)
            );

            perbaruiKonfirmasi();
            perbaruiTombolSubmit();
        }

        function perbaruiKonfirmasi() {
            pembungkusKonfirmasi.classList.remove(
                'border-red-500',
                'border-emerald-500'
            );

            if (!konfirmasi.value) {
                pesanKonfirmasi.classList.add('hidden');
                konfirmasi.setCustomValidity('');

                perbaruiTombolSubmit();

                return;
            }

            const sama =
                password.value === konfirmasi.value;

            pesanKonfirmasi.classList.remove('hidden');

            pesanKonfirmasi.textContent = sama
                ? 'Kata sandi sudah sama.'
                : 'Konfirmasi kata sandi belum sama.';

            pesanKonfirmasi.classList.toggle(
                'text-emerald-600',
                sama
            );

            pesanKonfirmasi.classList.toggle(
                'text-red-600',
                !sama
            );

            pembungkusKonfirmasi.classList.toggle(
                'border-emerald-500',
                sama
            );

            pembungkusKonfirmasi.classList.toggle(
                'border-red-500',
                !sama
            );

            konfirmasi.setCustomValidity(
                sama
                    ? ''
                    : 'Konfirmasi kata sandi belum sama.'
            );

            perbaruiTombolSubmit();
        }

        function perbaruiTombolSubmit() {
            const nikValid =
                inputNik.value.length === 16;

            const konfirmasiValid =
                konfirmasi.value.length > 0
                && password.value === konfirmasi.value;

            tombolSubmit.disabled = !(
                persetujuan.checked
                && nikValid
                && passwordValid()
                && konfirmasiValid
            );
        }

        inputNik.addEventListener('input', function () {
            perbaruiNik();
            perbaruiTombolSubmit();
        });

        password.addEventListener(
            'input',
            perbaruiPassword
        );

        konfirmasi.addEventListener(
            'input',
            perbaruiKonfirmasi
        );

        persetujuan.addEventListener(
            'change',
            perbaruiTombolSubmit
        );

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();

                return;
            }

            tombolSubmit.disabled = true;
            spinner.classList.remove('hidden');
            teksSubmit.textContent = 'Menyimpan...';
        });

        perbaruiNik();
        perbaruiPassword();
        perbaruiTombolSubmit();
    });
</script>
@endpush
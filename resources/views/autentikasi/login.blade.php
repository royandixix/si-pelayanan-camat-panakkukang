@extends('layouts.autentikasi')

@section('judul', 'Masuk Masyarakat')

@section(
    'deskripsi',
    'Masuk ke akun masyarakat Kecamatan Panakkukang.'
)

@section('lebar', 'max-w-md')

@php
    $errorLogin = $errors->getBag('login');

    if (! $errorLogin->any()) {
        $errorLogin = $errors->getBag('default');
    }
@endphp

@section('konten')
<section class="border border-zinc-200 bg-white shadow-[0_14px_35px_rgba(24,24,27,0.16)]">
    <div class="p-6 sm:p-8">
        <header class="border-b border-zinc-200 pb-5">
            <h2 class="text-2xl font-semibold tracking-tight text-zinc-800">
                Masuk
            </h2>

            <p class="mt-2 text-sm leading-6 text-zinc-500">
                Masukkan informasi akun masyarakat Anda.
            </p>
        </header>

        <form
            id="form-login"
            method="POST"
            action="{{ route('login.store') }}"
            class="mt-6 space-y-5"
        >
            @csrf

            <div>
                <label
                    for="login_email"
                    class="mb-2 block text-sm font-semibold text-zinc-800"
                >
                    Alamat email
                </label>

                <input
                    id="login_email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="nama@email.com"
                    @class([
                        'block w-full border bg-white px-3.5 py-3 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400',
                        'border-red-500 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errorLogin->has('email'),
                        'border-zinc-400 hover:border-zinc-600 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => ! $errorLogin->has('email'),
                    ])
                >

                @if ($errorLogin->has('email'))
                    <p class="mt-2 text-xs text-red-600">
                        {{ $errorLogin->first('email') }}
                    </p>
                @endif
            </div>

            <div>
                <label
                    for="login_password"
                    class="mb-2 block text-sm font-semibold text-zinc-800"
                >
                    Kata sandi
                </label>

                <div
                    @class([
                        'flex overflow-hidden border bg-white transition focus-within:ring-2',
                        'border-red-500 focus-within:border-red-600 focus-within:ring-red-100' => $errorLogin->has('password'),
                        'border-zinc-400 hover:border-zinc-600 focus-within:border-zinc-900 focus-within:ring-zinc-200' => ! $errorLogin->has('password'),
                    ])
                >
                    <input
                        id="login_password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan kata sandi"
                        class="min-w-0 flex-1 border-0 bg-transparent px-3.5 py-3 text-sm text-zinc-900 outline-none placeholder:text-zinc-400 focus:ring-0"
                    >

                    <button
                        id="tombol-password-login"
                        type="button"
                        class="shrink-0 border-l border-zinc-300 px-4 text-sm font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-950"
                        aria-controls="login_password"
                        aria-label="Tampilkan kata sandi"
                    >
                        Tampilkan
                    </button>
                </div>

                @if ($errorLogin->has('password'))
                    <p class="mt-2 text-xs text-red-600">
                        {{ $errorLogin->first('password') }}
                    </p>
                @endif
            </div>

            <label class="flex cursor-pointer items-start gap-3">
                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    @checked(old('remember'))
                    class="mt-0.5 h-4 w-4 border-zinc-400 text-zinc-900 focus:ring-zinc-500"
                >

                <span class="text-sm leading-5 text-zinc-600">
                    Ingat akun pada perangkat ini
                </span>
            </label>

            <button
                id="tombol-login"
                type="submit"
                class="inline-flex w-full items-center justify-center gap-2 bg-zinc-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-zinc-300"
            >
                <svg
                    id="spinner-login"
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

                <span id="teks-tombol-login">
                    Masuk
                </span>
            </button>
        </form>
    </div>
</section>

<div class="mt-7 text-center">
    <p class="text-sm text-zinc-700">
        Belum memiliki akun?
    </p>

    <a
        href="{{ route('register') }}"
        class="mt-3 inline-block text-sm font-semibold text-zinc-800 underline decoration-zinc-400 underline-offset-4 transition hover:text-black"
    >
        Daftar sebagai masyarakat
    </a>
</div>
@endsection

@push('skrip')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputPassword = document.getElementById(
            'login_password'
        );

        const tombolPassword = document.getElementById(
            'tombol-password-login'
        );

        const formLogin = document.getElementById(
            'form-login'
        );

        const tombolLogin = document.getElementById(
            'tombol-login'
        );

        const teksTombol = document.getElementById(
            'teks-tombol-login'
        );

        const spinner = document.getElementById(
            'spinner-login'
        );

        tombolPassword?.addEventListener('click', function () {
            const sedangTerlihat =
                inputPassword.type === 'text';

            inputPassword.type = sedangTerlihat
                ? 'password'
                : 'text';

            tombolPassword.textContent = sedangTerlihat
                ? 'Tampilkan'
                : 'Sembunyikan';

            tombolPassword.setAttribute(
                'aria-label',
                sedangTerlihat
                    ? 'Tampilkan kata sandi'
                    : 'Sembunyikan kata sandi'
            );

            inputPassword.focus();
        });

        formLogin?.addEventListener('submit', function (event) {
            if (!formLogin.checkValidity()) {
                event.preventDefault();
                formLogin.reportValidity();

                return;
            }

            tombolLogin.disabled = true;
            spinner.classList.remove('hidden');
            teksTombol.textContent = 'Memproses...';
        });
    });
</script>
@endpush
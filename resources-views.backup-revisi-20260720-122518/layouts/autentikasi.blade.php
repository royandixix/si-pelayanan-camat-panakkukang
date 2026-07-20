<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="@yield(
            'deskripsi',
            'Autentikasi layanan masyarakat Kecamatan Panakkukang'
        )"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('judul', 'Autentikasi Masyarakat')
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
    >

    @vite('resources/css/app.css')

    <style>
        body {
            font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #18181b;
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset;
        }

        .swal2-popup {
            border: 1px solid #e4e4e7 !important;
            border-radius: 0 !important;
            padding: 30px !important;
            font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif !important;
            box-shadow: 0 24px 70px rgba(24, 24, 27, 0.22) !important;
        }

        .swal2-title {
            color: #18181b !important;
            font-size: 21px !important;
            font-weight: 600 !important;
            line-height: 1.4 !important;
        }

        .swal2-html-container {
            margin-top: 14px !important;
            color: #52525b !important;
            font-size: 13px !important;
            line-height: 1.7 !important;
        }

        .swal2-actions {
            gap: 10px !important;
            margin-top: 24px !important;
        }

        .swal2-confirm,
        .swal2-cancel {
            border-radius: 0 !important;
            padding: 11px 20px !important;
            box-shadow: none !important;
            font-family: inherit !important;
            font-size: 13px !important;
            font-weight: 600 !important;
        }
    </style>

    @stack('gaya')
</head>

<body class="min-h-screen bg-[#f7f7f7] text-zinc-900 antialiased">
    <div
        class="fixed inset-x-0 top-0 z-50 h-2 bg-gradient-to-r from-orange-500 via-red-500 to-fuchsia-500"
        aria-hidden="true"
    ></div>

    <div class="mx-auto flex min-h-screen w-full max-w-7xl flex-col px-5 py-10 sm:px-8">
        <header class="flex shrink-0 justify-center pt-8 text-center">
            <div class="w-full max-w-xl">
                <div class="mx-auto flex h-12 w-12 items-center justify-center bg-white text-zinc-900 shadow-sm">
                    <svg
                        class="h-6 w-6"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="M4 21h16"></path>
                        <path d="M6 21V9l6-5 6 5v12"></path>
                        <path d="M9 21v-6h6v6"></path>
                        <path d="M9 11h.01"></path>
                        <path d="M15 11h.01"></path>
                    </svg>
                </div>

                <p class="mt-5 text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500">
                    Portal Pelayanan Masyarakat
                </p>

                @if (request()->routeIs('register'))
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight text-zinc-900">
                        Buat akun pelayanan masyarakat
                    </h1>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-zinc-500">
                        Daftarkan akun Anda untuk mengajukan pelayanan dan
                        memantau perkembangan permohonan secara daring.
                    </p>
                @else
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight text-zinc-900">
                        Akses layanan Kecamatan Panakkukang
                    </h1>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-zinc-500">
                        Masuk menggunakan akun masyarakat untuk mengakses
                        permohonan, dokumen, dan informasi pelayanan.
                    </p>
                @endif
            </div>
        </header>

        <main class="flex flex-1 items-start justify-center py-8">
            <div class="w-full @yield('lebar', 'max-w-md')">
                @yield('konten')
            </div>
        </main>

        <footer class="shrink-0 pb-2 text-center text-xs text-zinc-400">
            © {{ now()->year }} Kecamatan Panakkukang
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('skrip')

    @php
        $errorBagAktif = request()->routeIs('register')
            ? $errors->getBag('register')
            : $errors->getBag('login');

        if (! $errorBagAktif->any()) {
            $errorBagAktif = $errors->getBag('default');
        }

        $judulValidasi = request()->routeIs('register')
            ? 'Periksa data pendaftaran'
            : 'Login belum berhasil';
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const notifikasi = @json(session('swal'));
            const daftarError = @json($errorBagAktif->all());
            const judulValidasi = @json($judulValidasi);

            if (typeof window.Swal === 'undefined') {
                return;
            }

            function buatDaftarError(pesanError) {
                const pembungkus = document.createElement('div');

                pembungkus.style.textAlign = 'left';

                pesanError.forEach(function (pesan, index) {
                    const baris = document.createElement('div');

                    baris.style.display = 'flex';
                    baris.style.alignItems = 'flex-start';
                    baris.style.gap = '10px';
                    baris.style.padding = '8px 0';

                    if (index < pesanError.length - 1) {
                        baris.style.borderBottom =
                            '1px solid #e4e4e7';
                    }

                    const indikator =
                        document.createElement('span');

                    indikator.textContent = '•';
                    indikator.style.color = '#dc2626';
                    indikator.style.fontWeight = '700';
                    indikator.style.lineHeight = '1.7';

                    const teks =
                        document.createElement('span');

                    teks.textContent = pesan;
                    teks.style.color = '#52525b';
                    teks.style.fontSize = '13px';
                    teks.style.lineHeight = '1.7';

                    baris.appendChild(indikator);
                    baris.appendChild(teks);
                    pembungkus.appendChild(baris);
                });

                return pembungkus;
            }

            if (notifikasi) {
                window.Swal.fire({
                    icon: notifikasi.icon ?? 'info',
                    title: notifikasi.title ?? 'Informasi',
                    text: notifikasi.text ?? '',
                    confirmButtonText:
                        notifikasi.confirmButtonText ?? 'Mengerti',
                    confirmButtonColor: '#18181b',
                    allowOutsideClick: false,
                });

                return;
            }

            if (daftarError.length > 0) {
                window.Swal.fire({
                    icon: 'error',
                    title: judulValidasi,
                    html: buatDaftarError(daftarError),
                    confirmButtonText: 'Periksa kembali',
                    confirmButtonColor: '#18181b',
                });
            }
        });
    </script>
</body>
</html>
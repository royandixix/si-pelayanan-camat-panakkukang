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
            'Portal Pelayanan Masyarakat Kecamatan Panakkukang'
        )"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('judul', 'Portal Masyarakat')
        — Kecamatan Panakkukang
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    {{-- SweetAlert2 --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
    >

    {{-- Hanya Tailwind CSS --}}
    @vite('resources/css/app.css')

    <style>
        body {
            font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
        }

        [hidden] {
            display: none !important;
        }

        .swal2-popup {
            border: 1px solid #e4e4e7 !important;
            border-radius: 16px !important;
            padding: 28px !important;
            font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif !important;
            box-shadow: 0 24px 70px rgba(24, 24, 27, 0.20) !important;
        }

        .swal2-title {
            color: #18181b !important;
            font-size: 21px !important;
            font-weight: 600 !important;
        }

        .swal2-html-container {
            color: #52525b !important;
            font-size: 13px !important;
            line-height: 1.7 !important;
        }

        .swal2-confirm,
        .swal2-cancel {
            border-radius: 10px !important;
            box-shadow: none !important;
            font-family: inherit !important;
            font-size: 13px !important;
            font-weight: 600 !important;
        }
    </style>

    @stack('gaya')
</head>

<body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased">
    {{-- Garis gradasi atas --}}
    <div
        class="fixed inset-x-0 top-0 z-[60] h-1.5 bg-gradient-to-r from-orange-500 via-red-500 to-fuchsia-500"
        aria-hidden="true"
    ></div>

    <div class="flex min-h-screen flex-col pt-1.5">
        @include('partials.masyarakat.navbar')

        <main class="flex-1">
            <div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                @include('partials.masyarakat.header')

                @if (session('berhasil'))
                    <div
                        class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
                        role="alert"
                    >
                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            aria-hidden="true"
                        >
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="m8 12 2.5 2.5L16 9"></path>
                        </svg>

                        <span>{{ session('berhasil') }}</span>
                    </div>
                @endif

                @if (session('gagal'))
                    <div
                        class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
                        role="alert"
                    >
                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            aria-hidden="true"
                        >
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 8v5"></path>
                            <path d="M12 16h.01"></path>
                        </svg>

                        <span>{{ session('gagal') }}</span>
                    </div>
                @endif

                @yield('konten')
            </div>
        </main>

        @include('partials.masyarakat.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('skrip')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tombolMenuMobile = document.getElementById(
                'tombol-menu-mobile'
            );

            const menuMobile = document.getElementById(
                'menu-mobile'
            );

            const ikonMenu = document.getElementById(
                'ikon-menu'
            );

            const ikonTutup = document.getElementById(
                'ikon-tutup'
            );

            const tombolProfil = document.getElementById(
                'tombol-menu-profil'
            );

            const menuProfil = document.getElementById(
                'menu-profil'
            );

            const tombolNotifikasi = document.getElementById(
                'tombol-notifikasi'
            );

            const menuNotifikasi = document.getElementById(
                'menu-notifikasi'
            );

            function sembunyikanMenuProfil() {
                if (!menuProfil || !tombolProfil) {
                    return;
                }

                menuProfil.classList.add('hidden');
                tombolProfil.setAttribute(
                    'aria-expanded',
                    'false'
                );
            }

            function sembunyikanNotifikasi() {
                if (!menuNotifikasi || !tombolNotifikasi) {
                    return;
                }

                menuNotifikasi.classList.add('hidden');
                tombolNotifikasi.setAttribute(
                    'aria-expanded',
                    'false'
                );
            }

            tombolMenuMobile?.addEventListener(
                'click',
                function () {
                    const sedangTertutup =
                        menuMobile.classList.contains('hidden');

                    menuMobile.classList.toggle('hidden');

                    ikonMenu?.classList.toggle(
                        'hidden',
                        sedangTertutup
                    );

                    ikonTutup?.classList.toggle(
                        'hidden',
                        !sedangTertutup
                    );

                    tombolMenuMobile.setAttribute(
                        'aria-expanded',
                        String(sedangTertutup)
                    );
                }
            );

            tombolProfil?.addEventListener(
                'click',
                function (event) {
                    event.stopPropagation();

                    const sedangTertutup =
                        menuProfil.classList.contains('hidden');

                    sembunyikanNotifikasi();

                    menuProfil.classList.toggle('hidden');

                    tombolProfil.setAttribute(
                        'aria-expanded',
                        String(sedangTertutup)
                    );
                }
            );

            tombolNotifikasi?.addEventListener(
                'click',
                function (event) {
                    event.stopPropagation();

                    const sedangTertutup =
                        menuNotifikasi.classList.contains(
                            'hidden'
                        );

                    sembunyikanMenuProfil();

                    menuNotifikasi.classList.toggle('hidden');

                    tombolNotifikasi.setAttribute(
                        'aria-expanded',
                        String(sedangTertutup)
                    );
                }
            );

            menuProfil?.addEventListener(
                'click',
                function (event) {
                    event.stopPropagation();
                }
            );

            menuNotifikasi?.addEventListener(
                'click',
                function (event) {
                    event.stopPropagation();
                }
            );

            document.addEventListener('click', function () {
                sembunyikanMenuProfil();
                sembunyikanNotifikasi();
            });

            document.addEventListener(
                'keydown',
                function (event) {
                    if (event.key !== 'Escape') {
                        return;
                    }

                    sembunyikanMenuProfil();
                    sembunyikanNotifikasi();

                    if (
                        menuMobile &&
                        !menuMobile.classList.contains('hidden')
                    ) {
                        menuMobile.classList.add('hidden');
                        ikonMenu?.classList.remove('hidden');
                        ikonTutup?.classList.add('hidden');

                        tombolMenuMobile?.setAttribute(
                            'aria-expanded',
                            'false'
                        );
                    }
                }
            );

            document
                .querySelectorAll('[data-form-logout]')
                .forEach(function (formLogout) {
                    formLogout.addEventListener(
                        'submit',
                        function (event) {
                            event.preventDefault();

                            if (
                                typeof window.Swal ===
                                'undefined'
                            ) {
                                const yakin = window.confirm(
                                    'Apakah Anda yakin ingin keluar?'
                                );

                                if (yakin) {
                                    formLogout.submit();
                                }

                                return;
                            }

                            window.Swal.fire({
                                icon: 'question',
                                title: 'Keluar dari akun?',
                                text: 'Anda perlu masuk kembali untuk mengakses layanan masyarakat.',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, keluar',
                                cancelButtonText: 'Batal',
                                confirmButtonColor: '#18181b',
                                cancelButtonColor: '#71717a',
                                reverseButtons: true,
                            }).then(function (hasil) {
                                if (hasil.isConfirmed) {
                                    formLogout.submit();
                                }
                            });
                        }
                    );
                });

            const notifikasi = @json(session('swal'));

            if (
                notifikasi &&
                typeof window.Swal !== 'undefined'
            ) {
                window.Swal.fire({
                    icon: notifikasi.icon ?? 'info',
                    title:
                        notifikasi.title ?? 'Informasi',
                    text:
                        notifikasi.text ?? '',
                    confirmButtonText:
                        notifikasi.confirmButtonText
                        ?? 'Mengerti',
                    confirmButtonColor: '#18181b',
                });
            }
        });
    </script>
</body>
</html>
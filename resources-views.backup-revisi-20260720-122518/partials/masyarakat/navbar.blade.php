@php
    $pengguna = auth()->user();
    $namaPengguna = $pengguna?->name ?? 'Masyarakat';
    $emailPengguna = $pengguna?->email ?? '-';
    $inisial = mb_strtoupper(mb_substr(trim($namaPengguna), 0, 1)) ?: 'M';

    $fotoProfil = $pengguna?->profile_photo
        && \Illuminate\Support\Facades\Storage::disk('public')->exists($pengguna->profile_photo)
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($pengguna->profile_photo)
            : null;

    $punyaRouteNotifikasi = \Illuminate\Support\Facades\Route::has('masyarakat.notifikasi.index');
    $punyaRouteProfil = \Illuminate\Support\Facades\Route::has('masyarakat.profil.index');

    $menuNavigasi = [
        [
            'nama' => 'Dashboard',
            'url' => route('masyarakat.dashboard'),
            'aktif' => request()->routeIs('masyarakat.dashboard'),
            'ikon' => 'dashboard',
        ],
        [
            'nama' => 'Layanan',
            'url' => route('masyarakat.layanan.index'),
            'aktif' => request()->routeIs('masyarakat.layanan.*'),
            'ikon' => 'layanan',
        ],
        [
            'nama' => 'Permohonan Saya',
            'url' => route('masyarakat.permohonan.index'),
            'aktif' => request()->routeIs('masyarakat.permohonan.*'),
            'ikon' => 'permohonan',
        ],
        [
            'nama' => 'Antrean',
            'url' => route('masyarakat.antrean.index'),
            'aktif' => request()->routeIs('masyarakat.antrean.*'),
            'ikon' => 'antrean',
        ],
    ];
@endphp

<nav class="sticky top-1.5 z-40 border-b border-zinc-200 bg-white/95 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a
                href="{{ route('masyarakat.dashboard') }}"
                class="flex min-w-0 items-center gap-3"
            >
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-zinc-900 text-white">
                    <svg
                        class="h-5 w-5"
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
                </span>

                <span class="hidden min-w-0 sm:block">
                    <span class="block truncate text-sm font-semibold text-zinc-900">
                        Portal Masyarakat
                    </span>

                    <span class="block truncate text-[11px] text-zinc-500">
                        Kecamatan Panakkukang
                    </span>
                </span>
            </a>

            <div class="hidden items-center gap-1 lg:flex">
                @foreach ($menuNavigasi as $menu)
                    <a
                        href="{{ $menu['url'] }}"
                        @class([
                            'inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-sm font-medium transition',
                            'bg-zinc-900 text-white' => $menu['aktif'],
                            'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' => !$menu['aktif'],
                        ])
                    >
                        @if ($menu['ikon'] === 'dashboard')
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                            </svg>
                        @elseif ($menu['ikon'] === 'layanan')
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path d="M4 5h16"></path>
                                <path d="M4 12h16"></path>
                                <path d="M4 19h16"></path>
                                <circle cx="7" cy="5" r="1"></circle>
                                <circle cx="7" cy="12" r="1"></circle>
                                <circle cx="7" cy="19" r="1"></circle>
                            </svg>
                        @elseif ($menu['ikon'] === 'permohonan')
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-3-7 3V5a2 2 0 0 1 2-2Z"></path>
                            </svg>
                        @else
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path d="M5 5h14"></path>
                                <path d="M5 12h14"></path>
                                <path d="M5 19h14"></path>
                                <path d="m16 9 3 3-3 3"></path>
                            </svg>
                        @endif

                        {{ $menu['nama'] }}
                    </a>
                @endforeach
            </div>

            <div class="flex items-center gap-2">
                <div class="relative">
                    <button
                        id="tombol-notifikasi"
                        type="button"
                        class="relative flex h-10 w-10 items-center justify-center rounded-lg text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-950"
                        aria-label="Buka notifikasi"
                        aria-expanded="false"
                    >
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z"></path>
                            <path d="M10 21h4"></path>
                        </svg>
                    </button>

                    <div
                        id="menu-notifikasi"
                        class="absolute right-0 top-12 z-50 hidden w-[320px] max-w-[calc(100vw-2rem)] rounded-xl border border-zinc-200 bg-white shadow-xl"
                    >
                        <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3">
                            <p class="text-sm font-semibold text-zinc-900">
                                Notifikasi
                            </p>

                            @if ($punyaRouteNotifikasi)
                                <a
                                    href="{{ route('masyarakat.notifikasi.index') }}"
                                    class="text-xs font-medium text-zinc-500 transition hover:text-zinc-950"
                                >
                                    Lihat semua
                                </a>
                            @endif
                        </div>

                        <div class="px-5 py-8 text-center">
                            <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400">
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >
                                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z"></path>
                                    <path d="M10 21h4"></path>
                                </svg>
                            </span>

                            <p class="mt-3 text-sm font-medium text-zinc-800">
                                Belum ada notifikasi
                            </p>

                            <p class="mt-1 text-xs leading-5 text-zinc-500">
                                Informasi perkembangan permohonan akan muncul di sini.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="relative hidden sm:block">
                    <button
                        id="tombol-menu-profil"
                        type="button"
                        class="flex items-center gap-3 rounded-lg p-1.5 pr-3 transition hover:bg-zinc-100"
                        aria-expanded="false"
                    >
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-zinc-900 text-sm font-semibold text-white">
                            @if ($fotoProfil)
                                <img
                                    src="{{ $fotoProfil }}"
                                    alt="Foto profil {{ $namaPengguna }}"
                                    class="h-full w-full object-cover"
                                >
                            @else
                                {{ $inisial }}
                            @endif
                        </span>

                        <span class="hidden max-w-32 text-left md:block">
                            <span class="block truncate text-xs font-semibold text-zinc-900">
                                {{ $namaPengguna }}
                            </span>

                            <span class="block truncate text-[10px] text-zinc-500">
                                Masyarakat
                            </span>
                        </span>

                        <svg
                            class="h-4 w-4 text-zinc-400"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            aria-hidden="true"
                        >
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </button>

                    <div
                        id="menu-profil"
                        class="absolute right-0 top-12 z-50 hidden w-72 rounded-xl border border-zinc-200 bg-white p-2 shadow-xl"
                    >
                        <div class="flex items-center gap-3 border-b border-zinc-100 px-3 py-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-zinc-900 text-sm font-semibold text-white">
                                @if ($fotoProfil)
                                    <img
                                        src="{{ $fotoProfil }}"
                                        alt="Foto profil {{ $namaPengguna }}"
                                        class="h-full w-full object-cover"
                                    >
                                @else
                                    {{ $inisial }}
                                @endif
                            </span>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-zinc-900">
                                    {{ $namaPengguna }}
                                </p>

                                <p class="mt-1 truncate text-xs text-zinc-500">
                                    {{ $emailPengguna }}
                                </p>
                            </div>
                        </div>

                        <div class="py-2">
                            @if ($punyaRouteProfil)
                                <a
                                    href="{{ route('masyarakat.profil.index') }}"
                                    @class([
                                        'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition',
                                        'bg-zinc-900 text-white' => request()->routeIs('masyarakat.profil.*'),
                                        'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' => !request()->routeIs('masyarakat.profil.*'),
                                    ])
                                >
                                    <svg
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        aria-hidden="true"
                                    >
                                        <circle cx="12" cy="8" r="4"></circle>
                                        <path d="M4 21a8 8 0 0 1 16 0"></path>
                                    </svg>

                                    Profil saya
                                </a>
                            @else
                                <span class="flex cursor-not-allowed items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-zinc-300">
                                    <svg
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        aria-hidden="true"
                                    >
                                        <circle cx="12" cy="8" r="4"></circle>
                                        <path d="M4 21a8 8 0 0 1 16 0"></path>
                                    </svg>

                                    Profil saya
                                </span>
                            @endif
                        </div>

                        <div class="border-t border-zinc-100 pt-2">
                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                                data-form-logout
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm text-red-600 transition hover:bg-red-50"
                                >
                                    <svg
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        aria-hidden="true"
                                    >
                                        <path d="M10 17l5-5-5-5"></path>
                                        <path d="M15 12H3"></path>
                                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                    </svg>

                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <button
                    id="tombol-menu-mobile"
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-lg text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-950 lg:hidden"
                    aria-label="Buka navigasi"
                    aria-expanded="false"
                >
                    <svg
                        id="ikon-menu"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        aria-hidden="true"
                    >
                        <path d="M4 6h16"></path>
                        <path d="M4 12h16"></path>
                        <path d="M4 18h16"></path>
                    </svg>

                    <svg
                        id="ikon-tutup"
                        class="hidden h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        aria-hidden="true"
                    >
                        <path d="m6 6 12 12"></path>
                        <path d="m18 6-12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div
            id="menu-mobile"
            class="hidden border-t border-zinc-200 py-3 lg:hidden"
        >
            <div class="mb-3 flex items-center gap-3 rounded-xl bg-zinc-50 px-4 py-3 sm:hidden">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-zinc-900 text-sm font-semibold text-white">
                    @if ($fotoProfil)
                        <img
                            src="{{ $fotoProfil }}"
                            alt="Foto profil {{ $namaPengguna }}"
                            class="h-full w-full object-cover"
                        >
                    @else
                        {{ $inisial }}
                    @endif
                </span>

                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-zinc-900">
                        {{ $namaPengguna }}
                    </p>

                    <p class="mt-1 truncate text-xs text-zinc-500">
                        {{ $emailPengguna }}
                    </p>
                </div>
            </div>

            <div class="space-y-1">
                @foreach ($menuNavigasi as $menu)
                    <a
                        href="{{ $menu['url'] }}"
                        @class([
                            'flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition',
                            'bg-zinc-900 text-white' => $menu['aktif'],
                            'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' => !$menu['aktif'],
                        ])
                    >
                        @if ($menu['ikon'] === 'dashboard')
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                            </svg>
                        @elseif ($menu['ikon'] === 'layanan')
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M4 5h16"></path>
                                <path d="M4 12h16"></path>
                                <path d="M4 19h16"></path>
                            </svg>
                        @elseif ($menu['ikon'] === 'permohonan')
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-3-7 3V5a2 2 0 0 1 2-2Z"></path>
                            </svg>
                        @else
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M5 5h14"></path>
                                <path d="M5 12h14"></path>
                                <path d="M5 19h14"></path>
                                <path d="m16 9 3 3-3 3"></path>
                            </svg>
                        @endif

                        {{ $menu['nama'] }}
                    </a>
                @endforeach

                @if ($punyaRouteNotifikasi)
                    <a
                        href="{{ route('masyarakat.notifikasi.index') }}"
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-950"
                    >
                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z"></path>
                            <path d="M10 21h4"></path>
                        </svg>

                        Notifikasi
                    </a>
                @else
                    <span class="flex cursor-not-allowed items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-zinc-300">
                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z"></path>
                            <path d="M10 21h4"></path>
                        </svg>

                        Notifikasi
                    </span>
                @endif

                @if ($punyaRouteProfil)
                    <a
                        href="{{ route('masyarakat.profil.index') }}"
                        @class([
                            'flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition',
                            'bg-zinc-900 text-white' => request()->routeIs('masyarakat.profil.*'),
                            'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' => !request()->routeIs('masyarakat.profil.*'),
                        ])
                    >
                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 21a8 8 0 0 1 16 0"></path>
                        </svg>

                        Profil
                    </a>
                @else
                    <span class="flex cursor-not-allowed items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-zinc-300">
                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 21a8 8 0 0 1 16 0"></path>
                        </svg>

                        Profil
                    </span>
                @endif

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    data-form-logout
                >
                    @csrf

                    <button
                        type="submit"
                        class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50"
                    >
                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M10 17l5-5-5-5"></path>
                            <path d="M15 12H3"></path>
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        </svg>

                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
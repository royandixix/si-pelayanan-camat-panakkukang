<section class="mb-7 border-b border-zinc-200 pb-6">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <nav
                class="mb-3 flex items-center gap-2 text-xs text-zinc-400"
                aria-label="Breadcrumb"
            >
                <a
                    href="{{ url('/masyarakat') }}"
                    class="transition hover:text-zinc-900"
                >
                    Portal Masyarakat
                </a>

                <svg
                    class="h-3.5 w-3.5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    aria-hidden="true"
                >
                    <path d="m9 18 6-6-6-6"></path>
                </svg>

                <span class="truncate font-medium text-zinc-600">
                    @yield('breadcrumb', 'Dashboard')
                </span>
            </nav>

            <h1 class="text-2xl font-semibold tracking-tight text-zinc-950 sm:text-3xl">
                @yield(
                    'judul_halaman',
                    'Dashboard Masyarakat'
                )
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-zinc-500">
                @yield(
                    'deskripsi_halaman',
                    'Kelola pengajuan dan pantau perkembangan pelayanan Anda.'
                )
            </p>
        </div>

        @hasSection('aksi_header')
            <div class="flex shrink-0 items-center gap-3">
                @yield('aksi_header')
            </div>
        @endif
    </div>
</section>
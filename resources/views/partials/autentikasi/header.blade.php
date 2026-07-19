<header class="border-b border-slate-100 bg-white px-5 py-4 sm:px-8 lg:px-12 xl:px-20">
    <div class="mx-auto flex w-full max-w-2xl items-center justify-between gap-4">
        <a
            href="{{ url('/') }}"
            class="flex items-center gap-3 lg:hidden"
        >
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-700 text-sm font-black text-white shadow-lg shadow-blue-700/20">
                KP
            </span>

            <span>
                <span class="block text-xs font-medium text-slate-500">
                    Kecamatan
                </span>

                <span class="block text-sm font-bold text-slate-900">
                    Panakkukang
                </span>
            </span>
        </a>

        <a
            href="{{ url('/') }}"
            class="hidden items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-blue-700 lg:inline-flex"
        >
            <svg
                class="h-4 w-4"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="m15 18-6-6 6-6"></path>
            </svg>

            Kembali ke beranda
        </a>

        <nav class="flex shrink-0 items-center rounded-xl bg-slate-100 p-1">
            <a
                href="{{ url('/login') }}"
                @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold transition',
                    'bg-white text-blue-700 shadow-sm' => request()->is('login'),
                    'text-slate-600 hover:text-slate-900' => ! request()->is('login'),
                ])
            >
                Masuk
            </a>

            <a
                href="{{ url('/register') }}"
                @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold transition',
                    'bg-white text-blue-700 shadow-sm' => request()->is('register'),
                    'text-slate-600 hover:text-slate-900' => ! request()->is('register'),
                ])
            >
                Daftar
            </a>
        </nav>
    </div>
</header>

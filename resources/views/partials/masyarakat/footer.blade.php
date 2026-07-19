<footer class="border-t border-zinc-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-6 text-xs text-zinc-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <p>
            © {{ now()->year }} Portal Pelayanan Masyarakat
        </p>

        <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
            <a
                href="{{ url('/masyarakat/bantuan') }}"
                class="transition hover:text-zinc-950"
            >
                Bantuan
            </a>

            <a
                href="{{ url('/masyarakat/kebijakan-privasi') }}"
                class="transition hover:text-zinc-950"
            >
                Kebijakan privasi
            </a>

            <span class="text-zinc-400">
                Kecamatan Panakkukang
            </span>
        </div>
    </div>
</footer>
@php
    $statusBukti = $permohonan->status instanceof \BackedEnum
        ? $permohonan->status->value
        : (string) $permohonan->status;
@endphp

@if ($statusBukti !== 'draft')
    <section class="mb-6 overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
        <div class="h-1 bg-gradient-to-r from-zinc-900 via-zinc-600 to-zinc-200"></div>

        <div class="flex flex-col gap-5 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div class="flex min-w-0 gap-4">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-zinc-700">
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M6 3h9l3 3v15H6Z"></path>
                        <path d="M14 3v4h4"></path>
                        <path d="M9 12h6"></path>
                        <path d="M9 16h6"></path>
                    </svg>
                </span>

                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">
                        Tanda terima
                    </p>

                    <h2 class="mt-1 text-sm font-semibold text-zinc-900">
                        Bukti pengajuan permohonan
                    </h2>

                    <p class="mt-1 text-xs leading-5 text-zinc-500">
                        Lihat atau unduh bukti resmi pengajuan dengan nomor {{ $permohonan->registration_number }}.
                    </p>
                </div>
            </div>

            <div class="flex shrink-0 flex-col gap-2 sm:flex-row">
                <a
                    href="{{ route('masyarakat.permohonan.bukti.show', $permohonan) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-xs font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                        <circle cx="12" cy="12" r="2.5"></circle>
                    </svg>

                    Lihat bukti
                </a>

                <a
                    href="{{ route('masyarakat.permohonan.bukti.download', $permohonan) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-zinc-700"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M12 3v12"></path>
                        <path d="m7 10 5 5 5-5"></path>
                        <path d="M5 21h14"></path>
                    </svg>

                    Unduh PDF
                </a>
            </div>
        </div>
    </section>
@endif
@props([
    'layanan',
])

@php
    $standar = is_array($layanan->service_standard)
        ? $layanan->service_standard
        : [];

    $persyaratan = collect($standar['persyaratan'] ?? []);

    if ($persyaratan->isEmpty()) {
        $persyaratan = $layanan->requirements
            ->map(fn ($item) => [
                'nama' => $item->name,
                'wajib' => $item->is_required,
            ]);
    }

    $prosedur = collect($standar['prosedur'] ?? []);
    $produk = collect($standar['produk'] ?? []);
    $pengaduan = collect($standar['pengaduan'] ?? []);

    $modalId = 'standar-layanan-' . $layanan->id;
    $konfirmasiId = 'konfirmasi-standar-' . $layanan->id;
@endphp

<button
    type="button"
    data-buka-standar="{{ $modalId }}"
    class="inline-flex min-h-11 items-center justify-center rounded-md bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400"
>
    Lihat Syarat & Ajukan
</button>

<dialog
    id="{{ $modalId }}"
    data-modal-standar
    class="m-auto w-[calc(100%-2rem)] max-w-3xl rounded-lg border border-slate-200 bg-white p-0 shadow-2xl backdrop:bg-slate-950/60"
>
    <div class="flex max-h-[90vh] flex-col">
        <div class="flex items-start justify-between border-b border-slate-200 px-5 py-4 sm:px-6">
            <div class="pr-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Standar Pelayanan
                </p>
                <h2 class="mt-1 text-xl font-bold text-slate-950">
                    {{ $layanan->name }}
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $layanan->section?->name ?? 'Kantor Camat Panakkukang' }}
                </p>
            </div>

            <button
                type="button"
                data-tutup-standar="{{ $modalId }}"
                class="inline-flex size-10 shrink-0 items-center justify-center rounded-md border border-slate-200 text-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900"
                aria-label="Tutup"
            >
                ×
            </button>
        </div>

        <div class="overflow-y-auto px-5 py-5 sm:px-6">
            <section>
                <h3 class="text-sm font-bold uppercase tracking-wide text-slate-900">
                    Deskripsi Layanan
                </h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    {{ $layanan->description ?: 'Informasi pelayanan masyarakat Kantor Camat Panakkukang.' }}
                </p>
            </section>

            <section class="mt-6">
                <h3 class="text-sm font-bold uppercase tracking-wide text-slate-900">
                    Persyaratan
                </h3>

                <div class="mt-3 space-y-2">
                    @forelse($persyaratan as $index => $syarat)
                        <div class="flex gap-3 rounded-md border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-white text-xs font-bold text-slate-700 ring-1 ring-slate-200">
                                {{ $index + 1 }}
                            </span>

                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-800">
                                    {{ $syarat['nama'] ?? '-' }}
                                </p>

                                <span class="mt-1 inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ ($syarat['wajib'] ?? false) ? 'bg-red-100 text-red-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ ($syarat['wajib'] ?? false) ? 'Wajib' : 'Opsional' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-md border border-dashed border-slate-300 p-4 text-sm text-slate-500">
                            Persyaratan belum tersedia.
                        </p>
                    @endforelse
                </div>
            </section>

            @if($prosedur->isNotEmpty())
                <section class="mt-6">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-900">
                        Sistem, Mekanisme, dan Prosedur
                    </h3>

                    <ol class="mt-3 space-y-3">
                        @foreach($prosedur as $index => $langkah)
                            <li class="flex gap-3 text-sm leading-6 text-slate-600">
                                <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">
                                    {{ $index + 1 }}
                                </span>
                                <span>{{ $langkah }}</span>
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <section class="rounded-md border border-slate-200 p-4">
                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">
                        Jangka Waktu
                    </h3>
                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-900">
                        {{ $standar['jangka_waktu'] ?? ($layanan->processing_days ? $layanan->processing_days . ' hari kerja' : 'Menyesuaikan kelengkapan berkas') }}
                    </p>
                </section>

                <section class="rounded-md border border-slate-200 p-4">
                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">
                        Biaya atau Tarif
                    </h3>
                    <p class="mt-2 text-sm font-semibold leading-6 text-emerald-700">
                        {{ $standar['biaya'] ?? 'Gratis' }}
                    </p>
                </section>
            </div>

            @if($produk->isNotEmpty())
                <section class="mt-6">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-900">
                        Produk Pelayanan
                    </h3>

                    <ul class="mt-3 space-y-2">
                        @foreach($produk as $item)
                            <li class="flex gap-2 text-sm leading-6 text-slate-600">
                                <span class="mt-2 size-1.5 shrink-0 rounded-full bg-slate-900"></span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if($pengaduan->isNotEmpty())
                <section class="mt-6 rounded-md border border-blue-200 bg-blue-50 p-4">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-blue-950">
                        Pengaduan, Saran, dan Masukan
                    </h3>

                    <ul class="mt-2 space-y-1">
                        @foreach($pengaduan as $item)
                            <li class="text-sm leading-6 text-blue-800">
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <label
                for="{{ $konfirmasiId }}"
                class="mt-6 flex cursor-pointer items-start gap-3 rounded-md border border-amber-200 bg-amber-50 p-4"
            >
                <input
                    id="{{ $konfirmasiId }}"
                    type="checkbox"
                    data-konfirmasi-standar="{{ $modalId }}"
                    class="mt-1 size-4 rounded border-amber-400 text-slate-900 focus:ring-slate-500"
                >

                <span class="text-sm font-medium leading-6 text-amber-950">
                    Saya telah membaca dan memahami deskripsi, persyaratan, prosedur, jangka waktu, serta ketentuan layanan ini.
                </span>
            </label>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
            <button
                type="button"
                data-tutup-standar="{{ $modalId }}"
                class="inline-flex min-h-11 items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Tutup
            </button>

            <a
                href="{{ route('masyarakat.permohonan.create', [
                    'layanan' => $layanan->slug,
                ]) }}"
                data-lanjut-standar="{{ $modalId }}"
                aria-disabled="true"
                class="pointer-events-none inline-flex min-h-11 items-center justify-center rounded-md bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white opacity-40 transition hover:bg-blue-600"
            >
                Lanjut Ajukan
            </a>
        </div>
    </div>
</dialog>

@once
    @push('skrip')
        <script>
            document.addEventListener('click', function (event) {
                const tombolBuka = event.target.closest('[data-buka-standar]');

                if (tombolBuka) {
                    const modal = document.getElementById(
                        tombolBuka.dataset.bukaStandar,
                    );

                    if (modal) {
                        modal.showModal();
                    }

                    return;
                }

                const tombolTutup = event.target.closest(
                    '[data-tutup-standar]',
                );

                if (tombolTutup) {
                    const modal = document.getElementById(
                        tombolTutup.dataset.tutupStandar,
                    );

                    if (modal) {
                        modal.close();
                    }
                }
            });

            document.addEventListener('change', function (event) {
                const checkbox = event.target.closest(
                    '[data-konfirmasi-standar]',
                );

                if (! checkbox) {
                    return;
                }

                const tombolLanjut = document.querySelector(
                    '[data-lanjut-standar="' +
                    checkbox.dataset.konfirmasiStandar +
                    '"]',
                );

                if (! tombolLanjut) {
                    return;
                }

                tombolLanjut.classList.toggle(
                    'pointer-events-none',
                    ! checkbox.checked,
                );

                tombolLanjut.classList.toggle(
                    'opacity-40',
                    ! checkbox.checked,
                );

                tombolLanjut.setAttribute(
                    'aria-disabled',
                    checkbox.checked ? 'false' : 'true',
                );
            });

            document.querySelectorAll(
                'dialog[data-modal-standar]',
            ).forEach(function (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        modal.close();
                    }
                });

                modal.addEventListener('close', function () {
                    const checkbox = modal.querySelector(
                        '[data-konfirmasi-standar]',
                    );

                    const tombolLanjut = modal.querySelector(
                        '[data-lanjut-standar]',
                    );

                    if (checkbox) {
                        checkbox.checked = false;
                    }

                    if (tombolLanjut) {
                        tombolLanjut.classList.add(
                            'pointer-events-none',
                            'opacity-40',
                        );

                        tombolLanjut.setAttribute(
                            'aria-disabled',
                            'true',
                        );
                    }
                });
            });
        </script>
    @endpush
@endonce

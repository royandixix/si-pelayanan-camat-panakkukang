@props(['layanan'])

@php
    $modalId = 'standar-layanan-' . $layanan->id;

    $standar = is_array($layanan->service_standard)
        ? $layanan->service_standard
        : [];

    $persyaratan = $standar['persyaratan'] ?? [];
    $prosedur = $standar['prosedur'] ?? [];
    $jangkaWaktu = $standar['jangka_waktu'] ?? null;
    $biaya = $standar['biaya'] ?? null;
    $produk = $standar['produk'] ?? [];
    $pengaduan = $standar['pengaduan'] ?? [];
@endphp

<button
    type="button"
    onclick="document.getElementById('{{ $modalId }}').showModal()"
    class="inline-flex min-h-11 flex-1 items-center justify-center rounded-md bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"
>
    Standar Layanan
</button>

<dialog
    id="{{ $modalId }}"
    class="m-auto w-[calc(100%-2rem)] max-w-3xl rounded-xl border-0 bg-transparent p-0 backdrop:bg-slate-950/60"
>
    <div class="max-h-[85vh] overflow-y-auto rounded-xl bg-white shadow-2xl">
        <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-slate-200 bg-white px-6 py-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Standar Pelayanan
                </p>

                <h2 class="mt-1 text-xl font-bold text-slate-950">
                    {{ $layanan->name }}
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $layanan->section?->name ?? 'Kecamatan Panakkukang' }}
                </p>
            </div>

            <button
                type="button"
                onclick="document.getElementById('{{ $modalId }}').close()"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-slate-200 text-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900"
            >
                ×
            </button>
        </div>

        <div class="space-y-6 p-6">
            <section>
                <h3 class="text-sm font-bold text-slate-900">
                    Deskripsi Layanan
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    {{ $layanan->description ?: 'Belum ada deskripsi layanan.' }}
                </p>
            </section>

            <section>
                <h3 class="text-sm font-bold text-slate-900">
                    Persyaratan
                </h3>

                <div class="mt-3 space-y-2">
                    @if(count($persyaratan) > 0)
                        @foreach($persyaratan as $index => $syarat)
                            <div class="flex gap-3 rounded-lg border border-slate-200 p-3">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-700">
                                    {{ $index + 1 }}
                                </span>

                                <div>
                                    <p class="text-sm font-medium text-slate-800">
                                        {{ $syarat['nama'] ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ ($syarat['wajib'] ?? false) ? 'Wajib' : 'Opsional' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @elseif($layanan->requirements?->count())
                        @foreach($layanan->requirements as $index => $syarat)
                            <div class="flex gap-3 rounded-lg border border-slate-200 p-3">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-700">
                                    {{ $index + 1 }}
                                </span>

                                <div>
                                    <p class="text-sm font-medium text-slate-800">
                                        {{ $syarat->name }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $syarat->is_required ? 'Wajib' : 'Opsional' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-slate-500">
                            Belum ada persyaratan yang ditentukan.
                        </p>
                    @endif
                </div>
            </section>

            @if(count($prosedur) > 0)
                <section>
                    <h3 class="text-sm font-bold text-slate-900">
                        Prosedur Pelayanan
                    </h3>

                    <ol class="mt-3 space-y-3">
                        @foreach($prosedur as $index => $langkah)
                            <li class="flex gap-3">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">
                                    {{ $index + 1 }}
                                </span>

                                <p class="pt-1 text-sm leading-6 text-slate-600">
                                    {{ $langkah }}
                                </p>
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <section class="rounded-lg border border-slate-200 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Jangka Waktu
                    </p>

                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-900">
                        {{ $jangkaWaktu ?: ($layanan->processing_days ? $layanan->processing_days . ' hari kerja' : 'Menyesuaikan') }}
                    </p>
                </section>

                <section class="rounded-lg border border-slate-200 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Biaya
                    </p>

                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-900">
                        {{ $biaya ?: 'Tidak dipungut biaya' }}
                    </p>
                </section>
            </div>

            @if(count($produk) > 0)
                <section>
                    <h3 class="text-sm font-bold text-slate-900">
                        Produk Pelayanan
                    </h3>

                    <ul class="mt-3 space-y-2">
                        @foreach($produk as $itemProduk)
                            <li class="flex gap-2 text-sm leading-6 text-slate-600">
                                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-slate-900"></span>
                                <span>{{ $itemProduk }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if(count($pengaduan) > 0)
                <section>
                    <h3 class="text-sm font-bold text-slate-900">
                        Pengaduan dan Informasi
                    </h3>

                    <ul class="mt-3 space-y-2">
                        @foreach($pengaduan as $itemPengaduan)
                            <li class="flex gap-2 text-sm leading-6 text-slate-600">
                                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-slate-900"></span>
                                <span>{{ $itemPengaduan }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>

        <div class="sticky bottom-0 flex flex-col-reverse gap-2 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:justify-end">
            <button
                type="button"
                onclick="document.getElementById('{{ $modalId }}').close()"
                class="inline-flex min-h-11 items-center justify-center rounded-md border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Tutup
            </button>

            <a
                href="{{ route('masyarakat.layanan.show', ['layanan' => $layanan->slug]) }}"
                class="inline-flex min-h-11 items-center justify-center rounded-md bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"
            >
                Lihat Detail
            </a>
        </div>
    </div>
</dialog>
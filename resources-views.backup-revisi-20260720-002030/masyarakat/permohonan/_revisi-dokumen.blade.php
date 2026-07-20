@php
    $permohonan->loadMissing('documents.requirement');

    $statusPermohonan = $permohonan->status instanceof \BackedEnum
        ? $permohonan->status->value
        : (string) $permohonan->status;

    $statusDokumenBermasalah = [
        'revision',
        'rejected',
        'invalid',
        'needs_revision',
        'need_revision',
    ];

    $dokumenBermasalah = $permohonan->documents
        ->filter(function ($dokumen) use ($statusDokumenBermasalah) {
            $status = $dokumen->verification_status instanceof \BackedEnum
                ? $dokumen->verification_status->value
                : (string) $dokumen->verification_status;

            return filled($dokumen->verification_notes)
                || in_array(
                    $status,
                    $statusDokumenBermasalah,
                    true,
                );
        });
@endphp

@if ($statusPermohonan === 'revision')
    <section class="mb-6 overflow-hidden rounded-xl border border-amber-200 bg-white shadow-sm">
        <div class="h-1 bg-gradient-to-r from-amber-500 via-orange-400 to-amber-200"></div>

        <header class="border-b border-amber-100 bg-amber-50 px-5 py-5 sm:px-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M12 3 2.5 20h19Z"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                </span>

                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-amber-700">
                        Tindakan diperlukan
                    </p>

                    <h2 class="mt-1 text-base font-semibold text-amber-950">
                        Permohonan memerlukan perbaikan dokumen
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-amber-800">
                        Periksa catatan petugas, unggah dokumen pengganti, lalu kirim ulang permohonan untuk diverifikasi.
                    </p>
                </div>
            </div>
        </header>

        @error('dokumen')
            <div class="border-b border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700 sm:px-6">
                {{ $message }}
            </div>
        @enderror

        @if ($dokumenBermasalah->isNotEmpty())
            <div class="divide-y divide-zinc-100">
                @foreach ($dokumenBermasalah as $dokumen)
                    @php
                        $ekstensi = collect(
                            $dokumen->requirement?->allowed_extensions
                                ?: ['pdf', 'jpg', 'jpeg', 'png'],
                        )
                            ->map(
                                fn ($item) =>
                                    strtolower(trim((string) $item)),
                            )
                            ->filter()
                            ->unique()
                            ->values();

                        $maksimumKb = max(
                            100,
                            min(
                                (int) (
                                    $dokumen->requirement?->max_size_kb
                                    ?: 2048
                                ),
                                10240,
                            ),
                        );

                        $maksimumMb = round($maksimumKb / 1024, 2);

                        $accept = $ekstensi
                            ->map(fn ($item) => '.' . $item)
                            ->implode(',');
                    @endphp

                    <article class="px-5 py-6 sm:px-6">
                        <div class="grid gap-5 lg:grid-cols-[1fr_360px]">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-red-50 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-red-700">
                                        Perlu diperbaiki
                                    </span>

                                    @if ($dokumen->requirement?->is_required)
                                        <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-[10px] font-semibold text-zinc-600">
                                            Wajib
                                        </span>
                                    @endif
                                </div>

                                <h3 class="mt-3 text-sm font-semibold text-zinc-900">
                                    {{ $dokumen->requirement?->name ?? 'Dokumen persyaratan' }}
                                </h3>

                                @if ($dokumen->requirement?->description)
                                    <p class="mt-2 text-xs leading-5 text-zinc-500">
                                        {{ $dokumen->requirement->description }}
                                    </p>
                                @endif

                                <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4">
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-red-600">
                                        Catatan petugas
                                    </p>

                                    <p class="mt-2 text-sm leading-6 text-red-800">
                                        {{ $dokumen->verification_notes ?: 'Dokumen tidak sesuai. Silakan unggah dokumen pengganti yang benar.' }}
                                    </p>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs text-zinc-500">
                                    <span>
                                        Format:
                                        <strong class="font-semibold text-zinc-700">
                                            {{ strtoupper($ekstensi->implode(', ')) }}
                                        </strong>
                                    </span>

                                    <span>
                                        Maksimal:
                                        <strong class="font-semibold text-zinc-700">
                                            {{ $maksimumMb }} MB
                                        </strong>
                                    </span>
                                </div>

                                <a
                                    href="{{ route('masyarakat.permohonan.dokumen.download', [$permohonan, $dokumen]) }}"
                                    class="mt-4 inline-flex items-center gap-2 text-xs font-semibold text-zinc-600 transition hover:text-zinc-950"
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

                                    Unduh dokumen lama
                                </a>
                            </div>

                            <form
                                method="POST"
                                action="{{ route('masyarakat.permohonan.dokumen.update', [$permohonan, $dokumen]) }}"
                                enctype="multipart/form-data"
                                class="rounded-xl border border-zinc-200 bg-zinc-50 p-5"
                                data-form-revisi
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="dokumen_revisi_id"
                                    value="{{ $dokumen->id }}"
                                >

                                <label
                                    for="berkas-revisi-{{ $dokumen->id }}"
                                    class="block text-sm font-semibold text-zinc-900"
                                >
                                    Dokumen pengganti
                                </label>

                                <p class="mt-1 text-xs leading-5 text-zinc-500">
                                    Pilih dokumen baru yang sesuai dengan catatan petugas.
                                </p>

                                <label
                                    for="berkas-revisi-{{ $dokumen->id }}"
                                    class="mt-4 flex cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 bg-white px-4 py-7 text-center transition hover:border-zinc-900"
                                >
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-zinc-100 text-zinc-600">
                                        <svg
                                            class="h-5 w-5"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path d="M12 16V4"></path>
                                            <path d="m7 9 5-5 5 5"></path>
                                            <path d="M5 20h14"></path>
                                        </svg>
                                    </span>

                                    <span class="mt-3 text-xs font-semibold text-zinc-800">
                                        Pilih dokumen
                                    </span>

                                    <span
                                        id="nama-berkas-{{ $dokumen->id }}"
                                        class="mt-1 max-w-full truncate text-[11px] text-zinc-400"
                                    >
                                        Belum ada berkas dipilih
                                    </span>
                                </label>

                                <input
                                    id="berkas-revisi-{{ $dokumen->id }}"
                                    type="file"
                                    name="berkas"
                                    accept="{{ $accept }}"
                                    required
                                    class="sr-only"
                                    data-input-revisi
                                    data-nama-target="nama-berkas-{{ $dokumen->id }}"
                                    data-ekstensi="{{ $ekstensi->implode(',') }}"
                                    data-maksimum-kb="{{ $maksimumKb }}"
                                >

                                @if (
                                    (int) old('dokumen_revisi_id')
                                    === (int) $dokumen->id
                                )
                                    @error('berkas')
                                        <p class="mt-3 text-xs font-medium text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                @endif

                                <button
                                    type="submit"
                                    class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 py-3 text-xs font-semibold text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:bg-zinc-400"
                                >
                                    <span>Unggah dokumen pengganti</span>
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <footer class="border-t border-amber-100 bg-amber-50 px-5 py-4 sm:px-6">
                <p class="text-xs leading-5 text-amber-800">
                    Perbaiki seluruh dokumen di atas. Tombol kirim ulang akan tersedia setelah semua dokumen bermasalah diperbarui.
                </p>
            </footer>
        @else
            <div class="px-5 py-8 sm:px-6">
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="m8 12 3 3 5-6"></path>
                                </svg>
                            </span>

                            <div>
                                <h3 class="text-sm font-semibold text-emerald-900">
                                    Semua dokumen telah diperbarui
                                </h3>

                                <p class="mt-1 text-xs leading-5 text-emerald-800">
                                    Kirim ulang permohonan agar dokumen dapat diperiksa kembali oleh petugas.
                                </p>
                            </div>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('masyarakat.permohonan.kirim-ulang', $permohonan) }}"
                            data-form-kirim-ulang
                        >
                            @csrf

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800 disabled:cursor-not-allowed disabled:bg-emerald-400 sm:w-auto"
                            >
                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path d="M22 2 11 13"></path>
                                    <path d="m22 2-7 20-4-9-9-4Z"></path>
                                </svg>

                                <span>Kirim ulang permohonan</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endif

@push('skrip')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputRevisi = document.querySelectorAll('[data-input-revisi]');
        const formRevisi = document.querySelectorAll('[data-form-revisi]');
        const formKirimUlang = document.querySelector('[data-form-kirim-ulang]');

        function tampilkanPeringatan(judul, pesan) {
            if (typeof window.Swal !== 'undefined') {
                window.Swal.fire({
                    icon: 'error',
                    title: judul,
                    text: pesan,
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#18181b',
                });

                return;
            }

            window.alert(pesan);
        }

        inputRevisi.forEach(function (input) {
            input.addEventListener('change', function () {
                const berkas = input.files[0];
                const target = document.getElementById(
                    input.dataset.namaTarget,
                );

                if (!berkas) {
                    target.textContent = 'Belum ada berkas dipilih';
                    target.className = 'mt-1 max-w-full truncate text-[11px] text-zinc-400';
                    return;
                }

                const ekstensiDiizinkan = input.dataset.ekstensi
                    .split(',')
                    .map(function (item) {
                        return item.trim().toLowerCase();
                    });

                const bagianNama = berkas.name.split('.');
                const ekstensi = bagianNama.length > 1
                    ? bagianNama.pop().toLowerCase()
                    : '';

                const maksimumKb = Number(
                    input.dataset.maksimumKb,
                );

                if (!ekstensiDiizinkan.includes(ekstensi)) {
                    input.value = '';
                    target.textContent = 'Belum ada berkas dipilih';
                    target.className = 'mt-1 max-w-full truncate text-[11px] text-zinc-400';

                    tampilkanPeringatan(
                        'Format tidak didukung',
                        `Format dokumen harus ${ekstensiDiizinkan.join(', ').toUpperCase()}.`,
                    );

                    return;
                }

                if (berkas.size > maksimumKb * 1024) {
                    input.value = '';
                    target.textContent = 'Belum ada berkas dipilih';
                    target.className = 'mt-1 max-w-full truncate text-[11px] text-zinc-400';

                    tampilkanPeringatan(
                        'Ukuran terlalu besar',
                        `Ukuran dokumen maksimal ${(maksimumKb / 1024).toFixed(2)} MB.`,
                    );

                    return;
                }

                target.textContent = `${berkas.name} · ${(berkas.size / 1024 / 1024).toFixed(2)} MB`;
                target.className = 'mt-1 max-w-full truncate text-[11px] font-medium text-emerald-600';
            });
        });

        formRevisi.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                if (!form.reportValidity()) {
                    return;
                }

                function kirim() {
                    const tombol = form.querySelector('button[type="submit"]');
                    tombol.disabled = true;
                    tombol.querySelector('span').textContent = 'Mengunggah...';
                    form.submit();
                }

                if (typeof window.Swal === 'undefined') {
                    kirim();
                    return;
                }

                window.Swal.fire({
                    icon: 'question',
                    title: 'Unggah dokumen pengganti?',
                    text: 'Dokumen lama akan diganti dengan dokumen baru.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, unggah',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#18181b',
                }).then(function (hasil) {
                    if (hasil.isConfirmed) {
                        kirim();
                    }
                });
            });
        });

        formKirimUlang?.addEventListener('submit', function (event) {
            event.preventDefault();

            function kirimUlang() {
                const tombol = formKirimUlang.querySelector(
                    'button[type="submit"]',
                );

                tombol.disabled = true;
                tombol.querySelector('span').textContent = 'Mengirim...';
                formKirimUlang.submit();
            }

            if (typeof window.Swal === 'undefined') {
                kirimUlang();
                return;
            }

            window.Swal.fire({
                icon: 'question',
                title: 'Kirim ulang permohonan?',
                text: 'Dokumen akan dikirim kembali untuk diverifikasi petugas.',
                showCancelButton: true,
                confirmButtonText: 'Ya, kirim ulang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                confirmButtonColor: '#047857',
            }).then(function (hasil) {
                if (hasil.isConfirmed) {
                    kirimUlang();
                }
            });
        });
    });
</script>
@endpush
@extends('layouts.masyarakat')

@php
    $namaPengguna = $pengguna->name ?: 'Masyarakat';
    $inisial = mb_strtoupper(mb_substr(trim($namaPengguna), 0, 1)) ?: 'M';
    $punyaFotoTersimpan = filled($pengguna->profile_photo);

    $fotoProfil = $punyaFotoTersimpan
        && \Illuminate\Support\Facades\Storage::disk('public')->exists($pengguna->profile_photo)
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($pengguna->profile_photo)
            : null;
@endphp

@section('judul', 'Profil Saya')
@section('deskripsi', 'Kelola data dan foto profil akun masyarakat.')
@section('breadcrumb', 'Profil')
@section('judul_halaman', 'Profil Saya')
@section('deskripsi_halaman', 'Perbarui informasi pribadi dan foto profil Anda.')

@section('aksi_header')
<a
    href="{{ route('masyarakat.profil.kata-sandi') }}"
    class="inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950"
>
    <svg
        class="h-4 w-4"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
    >
        <rect x="5" y="10" width="14" height="10" rx="2"></rect>
        <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
    </svg>

    Ubah kata sandi
</a>
@endsection

@section('konten')
<div class="grid gap-6 lg:grid-cols-[320px_1fr]">
    <aside class="space-y-6">
        <section class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
            <div class="h-1 bg-gradient-to-r from-zinc-900 via-zinc-500 to-zinc-200"></div>

            <div class="p-6 text-center">
                <div class="relative mx-auto h-24 w-24 overflow-hidden rounded-2xl bg-zinc-900">
                    <img
                        id="foto-profil-samping"
                        src="{{ $fotoProfil ?? '' }}"
                        alt="Foto profil {{ $namaPengguna }}"
                        @class([
                            'h-full w-full object-cover',
                            'hidden' => !$fotoProfil,
                        ])
                    >

                    <span
                        id="inisial-profil-samping"
                        @class([
                            'flex h-full w-full items-center justify-center text-3xl font-semibold text-white',
                            'hidden' => $fotoProfil,
                        ])
                    >
                        {{ $inisial }}
                    </span>
                </div>

                <h2 class="mt-4 text-lg font-semibold text-zinc-900">
                    {{ $namaPengguna }}
                </h2>

                <p class="mt-1 break-all text-sm text-zinc-500">
                    {{ $pengguna->email }}
                </p>

                <span class="mt-4 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Akun aktif
                </span>
            </div>

            <dl class="divide-y divide-zinc-100 border-t border-zinc-200">
                <div class="px-5 py-4">
                    <dt class="text-[10px] font-medium uppercase tracking-wider text-zinc-400">
                        NIK
                    </dt>

                    <dd class="mt-1 text-sm font-semibold text-zinc-800">
                        {{ $pengguna->nik ?: '-' }}
                    </dd>
                </div>

                <div class="px-5 py-4">
                    <dt class="text-[10px] font-medium uppercase tracking-wider text-zinc-400">
                        Bergabung sejak
                    </dt>

                    <dd class="mt-1 text-sm font-semibold text-zinc-800">
                        {{ $pengguna->created_at?->format('d M Y') ?? '-' }}
                    </dd>
                </div>

                <div class="px-5 py-4">
                    <dt class="text-[10px] font-medium uppercase tracking-wider text-zinc-400">
                        Terakhir diperbarui
                    </dt>

                    <dd class="mt-1 text-sm font-semibold text-zinc-800">
                        {{ $pengguna->updated_at?->format('d M Y, H:i') ?? '-' }}
                    </dd>
                </div>
            </dl>
        </section>

        <section class="rounded-xl border border-blue-200 bg-blue-50 p-5">
            <div class="flex gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 11v5"></path>
                        <path d="M12 8h.01"></path>
                    </svg>
                </span>

                <div>
                    <h2 class="text-sm font-semibold text-blue-900">
                        Ketentuan foto
                    </h2>

                    <p class="mt-2 text-xs leading-5 text-blue-800">
                        Format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 3 MB dengan dimensi 300 × 300 px hingga 4000 × 4000 px.
                    </p>
                </div>
            </div>
        </section>
    </aside>

    <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
        <header class="border-b border-zinc-200 px-6 py-5">
            <p class="text-[11px] font-medium uppercase tracking-wider text-zinc-400">
                Informasi pribadi
            </p>

            <h2 class="mt-1 text-base font-semibold text-zinc-900">
                Edit profil
            </h2>

            <p class="mt-1 text-xs leading-5 text-zinc-500">
                Data terbaru akan digunakan untuk permohonan pelayanan berikutnya.
            </p>
        </header>

        <form
            id="form-profil"
            method="POST"
            action="{{ route('masyarakat.profil.update') }}"
            enctype="multipart/form-data"
            class="p-6"
        >
            @csrf
            @method('PATCH')

            @if ($errors->any())
                <div
                    class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4"
                    role="alert"
                >
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600">
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 8v5"></path>
                                <path d="M12 16h.01"></path>
                            </svg>
                        </span>

                        <div>
                            <p class="text-sm font-semibold text-red-800">
                                Data belum dapat disimpan
                            </p>

                            <ul class="mt-2 space-y-1 text-xs leading-5 text-red-700">
                                @foreach ($errors->all() as $pesan)
                                    <li>{{ $pesan }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-5">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                    <div class="relative h-24 w-24 shrink-0 overflow-hidden rounded-2xl bg-zinc-900">
                        <img
                            id="pratinjau-foto"
                            src="{{ $fotoProfil ?? '' }}"
                            alt="Pratinjau foto profil"
                            @class([
                                'h-full w-full object-cover',
                                'hidden' => !$fotoProfil,
                            ])
                        >

                        <span
                            id="pratinjau-inisial"
                            @class([
                                'flex h-full w-full items-center justify-center text-3xl font-semibold text-white',
                                'hidden' => $fotoProfil,
                            ])
                        >
                            {{ $inisial }}
                        </span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h3 class="text-sm font-semibold text-zinc-900">
                            Foto profil
                        </h3>

                        <p class="mt-1 text-xs leading-5 text-zinc-500">
                            Pilih gambar JPG, JPEG, PNG, atau WEBP. Maksimal 3 MB dengan dimensi minimal 300 × 300 px.
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <label
                                for="foto"
                                class="inline-flex cursor-pointer items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-zinc-700"
                            >
                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path d="M12 16V4"></path>
                                    <path d="m7 9 5-5 5 5"></path>
                                    <path d="M5 20h14"></path>
                                </svg>

                                Pilih foto
                            </label>

                            @if ($punyaFotoTersimpan)
                                <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                    <input
                                        id="hapus_foto"
                                        type="checkbox"
                                        name="hapus_foto"
                                        value="1"
                                        class="h-4 w-4 rounded border-red-300 text-red-600 focus:ring-red-500"
                                    >

                                    Hapus foto
                                </label>
                            @endif
                        </div>

                        <input
                            id="foto"
                            type="file"
                            name="foto"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="sr-only"
                        >

                        <p
                            id="nama-foto"
                            class="mt-3 truncate text-xs font-medium text-zinc-400"
                        >
                            Belum ada foto baru dipilih
                        </p>

                        @error('foto')
                            <p class="mt-2 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label
                        for="name"
                        class="mb-2 block text-sm font-semibold text-zinc-800"
                    >
                        Nama lengkap
                        <span class="text-red-600">*</span>
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $pengguna->name) }}"
                        required
                        minlength="3"
                        maxlength="255"
                        autocomplete="name"
                        placeholder="Masukkan nama lengkap"
                        @class([
                            'block w-full rounded-lg border bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400',
                            'border-red-400 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errors->has('name'),
                            'border-zinc-300 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => !$errors->has('name'),
                        ])
                    >

                    @error('name')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="nik"
                        class="mb-2 block text-sm font-semibold text-zinc-800"
                    >
                        NIK
                    </label>

                    <input
                        id="nik"
                        type="text"
                        value="{{ $pengguna->nik }}"
                        readonly
                        class="block w-full cursor-not-allowed rounded-lg border border-zinc-200 bg-zinc-100 px-4 py-3 text-sm text-zinc-500 outline-none"
                    >
                </div>

                <div>
                    <label
                        for="email"
                        class="mb-2 block text-sm font-semibold text-zinc-800"
                    >
                        Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        value="{{ $pengguna->email }}"
                        readonly
                        class="block w-full cursor-not-allowed rounded-lg border border-zinc-200 bg-zinc-100 px-4 py-3 text-sm text-zinc-500 outline-none"
                    >
                </div>

                <div class="sm:col-span-2">
                    <label
                        for="phone"
                        class="mb-2 block text-sm font-semibold text-zinc-800"
                    >
                        Nomor telepon
                        <span class="text-red-600">*</span>
                    </label>

                    <input
                        id="phone"
                        type="tel"
                        name="phone"
                        value="{{ old('phone', $pengguna->phone) }}"
                        required
                        maxlength="20"
                        inputmode="tel"
                        autocomplete="tel"
                        placeholder="Contoh: 081234567890"
                        @class([
                            'block w-full rounded-lg border bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400',
                            'border-red-400 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errors->has('phone'),
                            'border-zinc-300 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => !$errors->has('phone'),
                        ])
                    >

                    <p class="mt-2 text-xs leading-5 text-zinc-500">
                        Gunakan nomor telepon aktif agar petugas dapat menghubungi Anda.
                    </p>

                    @error('phone')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <div class="mb-2 flex items-center justify-between gap-4">
                        <label
                            for="address"
                            class="text-sm font-semibold text-zinc-800"
                        >
                            Alamat lengkap
                            <span class="text-red-600">*</span>
                        </label>

                        <span
                            id="jumlah-alamat"
                            class="text-xs font-medium text-zinc-400"
                        >
                            {{ mb_strlen(old('address', $pengguna->address ?? '')) }}/1000
                        </span>
                    </div>

                    <textarea
                        id="address"
                        name="address"
                        rows="5"
                        required
                        minlength="10"
                        maxlength="1000"
                        autocomplete="street-address"
                        placeholder="Masukkan alamat lengkap"
                        @class([
                            'block w-full resize-none rounded-lg border bg-white px-4 py-3 text-sm leading-6 text-zinc-900 outline-none transition placeholder:text-zinc-400',
                            'border-red-400 focus:border-red-600 focus:ring-2 focus:ring-red-100' => $errors->has('address'),
                            'border-zinc-300 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-200' => !$errors->has('address'),
                        ])
                    >{{ old('address', $pengguna->address) }}</textarea>

                    @error('address')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 border-t border-zinc-200 pt-6 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('masyarakat.dashboard') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-950"
                >
                    Batal
                </a>

                <button
                    id="tombol-simpan-profil"
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-zinc-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:bg-zinc-400"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M5 4h12l2 2v14H5Z"></path>
                        <path d="M8 4v6h8V4"></path>
                        <path d="M8 20v-6h8v6"></path>
                    </svg>

                    <span>Simpan perubahan</span>
                </button>
            </div>
        </form>
    </section>
</div>
@endsection

@push('skrip')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-profil');
        const foto = document.getElementById('foto');
        const namaFoto = document.getElementById('nama-foto');
        const pratinjauFoto = document.getElementById('pratinjau-foto');
        const pratinjauInisial = document.getElementById('pratinjau-inisial');
        const fotoSamping = document.getElementById('foto-profil-samping');
        const inisialSamping = document.getElementById('inisial-profil-samping');
        const hapusFoto = document.getElementById('hapus_foto');
        const alamat = document.getElementById('address');
        const jumlahAlamat = document.getElementById('jumlah-alamat');
        const tombol = document.getElementById('tombol-simpan-profil');
        const fotoAwal = @json($fotoProfil);
        const ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'webp'];
        const mimeDiizinkan = ['image/jpeg', 'image/png', 'image/webp'];
        const maksimalUkuran = 3 * 1024 * 1024;
        const dimensiMinimum = 300;
        const dimensiMaksimum = 4000;
        let urlPratinjau = null;

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

        function hapusUrlPratinjau() {
            if (!urlPratinjau) {
                return;
            }

            URL.revokeObjectURL(urlPratinjau);
            urlPratinjau = null;
        }

        function tampilkanGambar(url) {
            pratinjauFoto.src = url;
            pratinjauFoto.classList.remove('hidden');
            pratinjauInisial.classList.add('hidden');

            fotoSamping.src = url;
            fotoSamping.classList.remove('hidden');
            inisialSamping.classList.add('hidden');
        }

        function tampilkanInisial() {
            pratinjauFoto.removeAttribute('src');
            pratinjauFoto.classList.add('hidden');
            pratinjauInisial.classList.remove('hidden');

            fotoSamping.removeAttribute('src');
            fotoSamping.classList.add('hidden');
            inisialSamping.classList.remove('hidden');
        }

        function tampilkanKeadaanTersimpan() {
            hapusUrlPratinjau();

            if (hapusFoto?.checked) {
                tampilkanInisial();
                return;
            }

            if (fotoAwal) {
                tampilkanGambar(fotoAwal);
                return;
            }

            tampilkanInisial();
        }

        function resetInputFoto() {
            foto.value = '';
            namaFoto.textContent = 'Belum ada foto baru dipilih';
            namaFoto.className = 'mt-3 truncate text-xs font-medium text-zinc-400';
        }

        function tolakFoto(judul, pesan) {
            resetInputFoto();
            tampilkanKeadaanTersimpan();
            tampilkanPeringatan(judul, pesan);
        }

        foto.addEventListener('change', function () {
            const berkas = foto.files[0];

            if (!berkas) {
                resetInputFoto();
                tampilkanKeadaanTersimpan();
                return;
            }

            const bagianNama = berkas.name.split('.');
            const ekstensi = bagianNama.length > 1
                ? bagianNama.pop().toLowerCase()
                : '';

            if (
                !ekstensiDiizinkan.includes(ekstensi)
                || !mimeDiizinkan.includes(berkas.type)
            ) {
                tolakFoto(
                    'Format tidak didukung',
                    'Foto profil hanya boleh menggunakan format JPG, JPEG, PNG, atau WEBP.',
                );

                return;
            }

            if (berkas.size > maksimalUkuran) {
                tolakFoto(
                    'Ukuran terlalu besar',
                    'Ukuran foto profil maksimal 3 MB.',
                );

                return;
            }

            const pembaca = new FileReader();

            pembaca.onerror = function () {
                tolakFoto(
                    'Foto tidak dapat dibaca',
                    'Berkas foto yang dipilih tidak dapat diproses.',
                );
            };

            pembaca.onload = function (event) {
                const gambar = new Image();

                gambar.onerror = function () {
                    tolakFoto(
                        'Foto tidak valid',
                        'Berkas yang dipilih bukan gambar yang valid.',
                    );
                };

                gambar.onload = function () {
                    if (
                        gambar.width < dimensiMinimum
                        || gambar.height < dimensiMinimum
                        || gambar.width > dimensiMaksimum
                        || gambar.height > dimensiMaksimum
                    ) {
                        tolakFoto(
                            'Dimensi tidak sesuai',
                            'Dimensi foto minimal 300 × 300 px dan maksimal 4000 × 4000 px.',
                        );

                        return;
                    }

                    hapusUrlPratinjau();
                    urlPratinjau = URL.createObjectURL(berkas);
                    tampilkanGambar(urlPratinjau);

                    namaFoto.textContent = `${berkas.name} · ${(berkas.size / 1024 / 1024).toFixed(2)} MB`;
                    namaFoto.className = 'mt-3 truncate text-xs font-medium text-emerald-600';

                    if (hapusFoto) {
                        hapusFoto.checked = false;
                    }
                };

                gambar.src = event.target.result;
            };

            pembaca.readAsDataURL(berkas);
        });

        hapusFoto?.addEventListener('change', function () {
            resetInputFoto();
            hapusUrlPratinjau();

            if (hapusFoto.checked) {
                tampilkanInisial();
                namaFoto.textContent = 'Foto profil akan dihapus setelah perubahan disimpan';
                namaFoto.className = 'mt-3 truncate text-xs font-medium text-red-600';
                return;
            }

            tampilkanKeadaanTersimpan();
        });

        function perbaruiJumlahAlamat() {
            jumlahAlamat.textContent = `${alamat.value.length}/1000`;
        }

        alamat.addEventListener('input', perbaruiJumlahAlamat);
        perbaruiJumlahAlamat();

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (!form.reportValidity()) {
                return;
            }

            function simpan() {
                tombol.disabled = true;
                tombol.querySelector('span').textContent = 'Menyimpan...';
                form.submit();
            }

            if (typeof window.Swal === 'undefined') {
                simpan();
                return;
            }

            window.Swal.fire({
                icon: 'question',
                title: 'Simpan perubahan?',
                text: 'Data dan foto profil terbaru akan disimpan.',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                confirmButtonColor: '#18181b',
            }).then(function (hasil) {
                if (hasil.isConfirmed) {
                    simpan();
                }
            });
        });

        window.addEventListener('beforeunload', hapusUrlPratinjau);
    });
</script>
@endpush
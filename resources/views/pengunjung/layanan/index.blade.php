@extends('layouts.pengunjung')

@section('title', 'Layanan - Kecamatan Panakkukang')

@section('content')
<x-pengunjung.page-hero
    kicker="Pelayanan Publik"
    title="Layanan Kecamatan"
    description="Pilih pelayanan sesuai kebutuhan dan ketahui informasi serta persyaratannya sebelum mengajukan permohonan."
/>

<section class="public-section">
    <div class="public-container">
        <div data-public-reveal>
            <div class="public-kicker">
                Daftar Layanan
            </div>

            <h2 class="public-title-sm">
                Semua kebutuhan pelayanan dalam satu tempat.
            </h2>
        </div>

        <div class="public-service-grid">
            @forelse(($layanan ?? collect()) as $item)
                <article class="public-card public-service-card" data-public-reveal>
                    <span class="public-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M7 3h8l4 4v14H7z"/>
                            <path d="M15 3v5h5M10 13h6M10 17h6"/>
                        </svg>
                    </span>

                    <div style="margin-top:23px;color:#999;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;">
                        {{ data_get($item, 'section.name', 'Pelayanan Kecamatan') }}
                    </div>

                    <h3>{{ data_get($item, 'name', 'Pelayanan Kecamatan') }}</h3>

                    <p>
                        {{ \Illuminate\Support\Str::limit(data_get($item, 'description', 'Informasi pelayanan masyarakat Kecamatan Panakkukang.'), 150) }}
                    </p>

                    <div class="public-service-card-footer">
                        <span style="color:#777;font-size:11px;">
                            {{ data_get($item, 'processing_days') ? data_get($item, 'processing_days').' hari proses' : 'Informasi pelayanan' }}
                        </span>

                        @auth
                            <a
                                href="{{ route('masyarakat.layanan.show', $item) }}"
                                class="public-service-arrow"
                            >
                                →
                            </a>
                        @else
                            <a href="{{ url('/login') }}" class="public-service-arrow">
                                →
                            </a>
                        @endauth
                    </div>
                </article>
            @empty
                <article class="public-card public-service-card" data-public-reveal>
                    <span class="public-icon">!</span>
                    <h3>Belum ada layanan</h3>
                    <p>Data layanan belum tersedia untuk ditampilkan.</p>
                </article>
            @endforelse
        </div>
    </div>
</section>

<section class="public-section public-process">
    <div class="public-container">
        <div data-public-reveal>
            <div class="public-kicker">Proses Pengajuan</div>

            <h2 class="public-title-sm">
                Tidak perlu bingung memulai.
            </h2>
        </div>

        <div class="public-process-grid">
            @foreach([
                ['01', 'Daftar akun', 'Buat akun masyarakat untuk mengakses fitur pengajuan.'],
                ['02', 'Pilih layanan', 'Pilih jenis pelayanan yang sesuai kebutuhan Anda.'],
                ['03', 'Lengkapi berkas', 'Kirim data dan dokumen persyaratan yang dibutuhkan.'],
                ['04', 'Pantau status', 'Pantau proses permohonan hingga pelayanan selesai.'],
            ] as $step)
                <div class="public-process-item" data-public-reveal>
                    <div class="public-process-number">{{ $step[0] }}</div>
                    <h3>{{ $step[1] }}</h3>
                    <p>{{ $step[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

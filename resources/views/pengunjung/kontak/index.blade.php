@extends('layouts.pengunjung')

@section('title', 'Kontak - Kecamatan Panakkukang')

@section('content')
<x-pengunjung.page-hero
    kicker="Hubungi Kami"
    title="Kontak Kecamatan"
    description="Dapatkan informasi lebih lanjut mengenai pelayanan masyarakat dan kunjungi Kantor Kecamatan Panakkukang."
/>

<section class="public-section">
    <div class="public-container">
        <div class="public-contact-grid">
            <div data-public-reveal>
                <div class="public-kicker">
                    Informasi Kontak
                </div>

                <h2 class="public-title-sm">
                    Kami siap membantu masyarakat.
                </h2>

                <p class="public-copy" style="margin-top:24px;">
                    Hubungi atau kunjungi kantor Kecamatan Panakkukang jika
                    membutuhkan bantuan terkait informasi dan pelayanan.
                </p>

                <div class="public-contact-list">
                    <div class="public-contact-item">
                        <span class="public-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                <circle cx="12" cy="10" r="2.5"/>
                            </svg>
                        </span>

                        <div>
                            <strong style="font-size:13px;">Alamat</strong>
                            <p class="public-copy" style="margin:4px 0 0;font-size:12px;">
                                {{ data_get($kontak ?? [], 'alamat', 'Kecamatan Panakkukang, Kota Makassar, Sulawesi Selatan') }}
                            </p>
                        </div>
                    </div>

                    <div class="public-contact-item">
                        <span class="public-icon" style="background:#f1e7cf;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h4l2 5-3 2c1 3 3 5 6 6l2-3 5 2v4c0 1-1 2-2 2C9 22 2 15 2 6c0-1 1-2 2-2Z"/>
                            </svg>
                        </span>

                        <div>
                            <strong style="font-size:13px;">Telepon</strong>
                            <p class="public-copy" style="margin:4px 0 0;font-size:12px;">
                                {{ data_get($kontak ?? [], 'telepon', 'Informasi nomor telepon kecamatan') }}
                            </p>
                        </div>
                    </div>

                    <div class="public-contact-item">
                        <span class="public-icon" style="background:#edf1f5;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 5h18v14H3z"/>
                                <path d="m3 7 9 6 9-6"/>
                            </svg>
                        </span>

                        <div>
                            <strong style="font-size:13px;">Email</strong>
                            <p class="public-copy" style="margin:4px 0 0;font-size:12px;">
                                {{ data_get($kontak ?? [], 'email', 'Informasi email kecamatan') }}
                            </p>
                        </div>
                    </div>

                    <div class="public-contact-item">
                        <span class="public-icon" style="background:#e3ebf3;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3 2"/>
                            </svg>
                        </span>

                        <div>
                            <strong style="font-size:13px;">Jam Pelayanan</strong>
                            <p class="public-copy" style="margin:4px 0 0;font-size:12px;">
                                Senin - Kamis 08.00 - 16.00 WITA
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="public-map" data-public-reveal>
                <iframe
                    src="https://www.google.com/maps?q=Kantor%20Kecamatan%20Panakkukang%20Makassar&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>
</section>

<section class="public-section" style="padding-top:10px;">
    <div class="public-container">
        <div class="public-cta" data-public-reveal>
            <div class="public-cta-inner">
                <div>
                    <div class="public-kicker">Pelayanan</div>

                    <h2 class="public-title-sm">
                        Periksa layanan sebelum datang ke kantor.
                    </h2>
                </div>

                <a href="{{ url('/layanan') }}" class="public-button">
                    Lihat Layanan
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

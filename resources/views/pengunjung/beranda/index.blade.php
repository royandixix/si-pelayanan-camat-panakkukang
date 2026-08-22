@extends('layouts.pengunjung')

@section('title', 'Beranda - Kecamatan Panakkukang')

@section('content')
<section class="public-hero">
    <div class="public-container">
        <div class="public-hero-grid">
            <div class="public-hero-copy" data-public-reveal>
                <div class="public-chip">
                    <span class="public-chip-dot"></span>
                    Pelayanan publik berbasis digital
                </div>

                <h1 class="public-title">
                    Pelayanan kecamatan yang lebih mudah untuk masyarakat.
                </h1>

                <p class="public-copy">
                    Akses informasi pelayanan, lengkapi persyaratan, ajukan
                    permohonan, dan pantau prosesnya melalui satu sistem
                    pelayanan Kecamatan Panakkukang.
                </p>

                <div class="public-actions">
                    @guest
                        <a href="{{ url('/register') }}" class="public-button">
                            Mulai Pelayanan
                            <span>↗</span>
                        </a>
                    @else
                        <a href="{{ url('/masyarakat') }}" class="public-button">
                            Buka Dashboard
                            <span>↗</span>
                        </a>
                    @endguest

                    <a href="{{ url('/layanan') }}" class="public-button public-button-light">
                        Lihat Layanan
                    </a>
                </div>
            </div>

            <div class="public-art" data-public-reveal>
                <div class="public-art-shape"></div>
                <div class="public-art-ring"></div>
                <div class="public-art-dot"></div>

                <div class="public-floating-card public-floating-one">
                    <strong style="font-size:13px;">Pelayanan Online</strong>
                    <div style="margin-top:5px;color:#818181;font-size:11px;">
                        Aktif dan dapat diakses
                    </div>
                </div>

                <div class="public-dashboard-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:20px;">
                        <div>
                            <span style="color:#8a8a8a;font-size:11px;">Portal Pelayanan</span>
                            <h3 style="margin:5px 0 0;font-size:20px;letter-spacing:-.03em;">
                                Kecamatan Panakkukang
                            </h3>
                        </div>

                        <span class="public-icon">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18M6 21V8l6-4 6 4v13M9 12h6M9 16h6"/>
                            </svg>
                        </span>
                    </div>

                    <div class="public-mini-grid">
                        <div class="public-mini-card">
                            <span>Layanan</span>
                            <strong data-counter="{{ $jumlahLayanan ?? 0 }}">0</strong>
                        </div>

                        <div class="public-mini-card" style="background:#f1e7cf;">
                            <span>Seksi</span>
                            <strong data-counter="{{ $jumlahSeksi ?? 0 }}">0</strong>
                        </div>
                    </div>
                </div>

                <div class="public-floating-card public-floating-two">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span class="public-chip-dot"></span>
                        <div>
                            <strong style="display:block;font-size:12px;">Status Transparan</strong>
                            <span style="color:#818181;font-size:10px;">Pantau secara online</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="public-section">
    <div class="public-container">
        <div class="public-feature-grid">
            <article class="public-card public-feature-card" data-public-reveal>
                <span class="public-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                </span>

                <h3>Mudah digunakan.</h3>

                <p>
                    Proses pelayanan dirancang sederhana agar masyarakat
                    dapat memahami setiap tahapan dengan cepat.
                </p>
            </article>

            <article class="public-card public-feature-card public-reveal-delay-1" data-public-reveal>
                <span class="public-icon" style="background:#fff;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </span>

                <h3>Lebih efisien.</h3>

                <p>
                    Informasi dan pengajuan dapat dilakukan tanpa harus
                    selalu datang langsung ke kantor kecamatan.
                </p>
            </article>

            <article class="public-card public-feature-card public-reveal-delay-2" data-public-reveal>
                <span class="public-icon" style="background:#e5edf4;color:#1f3550;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19V9m5 10V5m5 14v-7m5 7V3"/>
                    </svg>
                </span>

                <h3>Status transparan.</h3>

                <p>
                    Masyarakat dapat memantau perkembangan permohonan
                    melalui dashboard secara terintegrasi.
                </p>
            </article>
        </div>
    </div>
</section>

<section class="public-section" style="background:#f7f7f5;">
    <div class="public-container">
        <div class="public-split">
            <div class="public-visual" data-public-reveal>
                <div class="public-visual-lime"></div>

                <div class="public-visual-building">
                    <div class="public-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 21h18M6 21V8l6-4 6 4v13M9 12h6M9 16h6"/>
                        </svg>
                    </div>

                    <h3 style="margin:24px 0 8px;font-size:24px;letter-spacing:-.04em;">
                        Kecamatan Panakkukang
                    </h3>

                    <p class="public-copy" style="margin:0;">
                        Kota Makassar, Sulawesi Selatan
                    </p>
                </div>
            </div>

            <div data-public-reveal>
                <div class="public-kicker">
                    Tentang Kami
                </div>

                <h2 class="public-title-sm">
                    Pelayanan publik yang dekat dengan masyarakat.
                </h2>

                <p class="public-copy" style="margin-top:25px;">
                    Kecamatan Panakkukang memberikan pelayanan pemerintahan
                    dan administrasi kepada masyarakat dengan terus
                    meningkatkan kualitas, keterbukaan informasi, dan
                    pemanfaatan teknologi.
                </p>

                <div class="public-actions">
                    <a href="{{ url('/profil') }}" class="public-button">
                        Kenali Kecamatan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="public-container">
        <div class="public-stat-grid">
            <div class="public-stat">
                <strong data-counter="{{ $jumlahLayanan ?? 0 }}">0</strong>
                <span>Jenis layanan</span>
            </div>

            <div class="public-stat">
                <strong data-counter="{{ $jumlahSeksi ?? 0 }}">0</strong>
                <span>Seksi pelayanan</span>
            </div>

            <div class="public-stat">
                <strong>24/7</strong>
                <span>Akses informasi</span>
            </div>

            <div class="public-stat">
                <strong>Online</strong>
                <span>Sistem pelayanan</span>
            </div>
        </div>
    </div>
</section>

<section class="public-section">
    <div class="public-container">
        <div data-public-reveal>
            <div class="public-kicker">
                Layanan
            </div>

            <h2 class="public-title-sm">
                Pilih layanan sesuai kebutuhan Anda.
            </h2>
        </div>

        <div class="public-service-grid">
            @forelse(($layanan ?? collect())->take(6) as $item)
                <article class="public-card public-service-card" data-public-reveal>
                    <span class="public-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M7 3h8l4 4v14H7z"/>
                            <path d="M15 3v5h5M10 13h6M10 17h6"/>
                        </svg>
                    </span>

                    <h3>
                        {{ data_get($item, 'name', 'Pelayanan Kecamatan') }}
                    </h3>

                    <p>
                        {{ \Illuminate\Support\Str::limit(data_get($item, 'description', 'Informasi pelayanan masyarakat Kecamatan Panakkukang.'), 125) }}
                    </p>

                    <div class="public-service-card-footer">
                        <span style="color:#888;font-size:11px;">
                            {{ data_get($item, 'section.name', 'Kecamatan Panakkukang') }}
                        </span>

                        <a href="{{ url('/layanan') }}" class="public-service-arrow">
                            →
                        </a>
                    </div>
                </article>
            @empty
                @for($i = 1; $i <= 3; $i++)
                    <article class="public-card public-service-card" data-public-reveal>
                        <span class="public-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M7 3h8l4 4v14H7z"/>
                                <path d="M15 3v5h5M10 13h6M10 17h6"/>
                            </svg>
                        </span>

                        <h3>Pelayanan Kecamatan</h3>

                        <p>
                            Informasi pelayanan masyarakat akan ditampilkan
                            pada bagian ini.
                        </p>

                        <div class="public-service-card-footer">
                            <span style="color:#888;font-size:11px;">
                                Kecamatan Panakkukang
                            </span>
                        </div>
                    </article>
                @endfor
            @endforelse
        </div>

        <div style="margin-top:40px;">
            <a href="{{ url('/layanan') }}" class="public-button">
                Semua Layanan
            </a>
        </div>
    </div>
</section>

<section class="public-section public-process">
    <div class="public-container">
        <div data-public-reveal>
            <div class="public-kicker">
                Cara Kerja
            </div>

            <h2 class="public-title-sm" style="max-width:650px;">
                Empat langkah untuk mendapatkan pelayanan.
            </h2>
        </div>

        <div class="public-process-grid">
            <div class="public-process-item" data-public-reveal>
                <div class="public-process-number">01</div>
                <h3>Daftar akun</h3>
                <p>Buat akun masyarakat dan lengkapi informasi diri Anda.</p>
            </div>

            <div class="public-process-item" data-public-reveal>
                <div class="public-process-number">02</div>
                <h3>Pilih layanan</h3>
                <p>Pilih jenis pelayanan sesuai kebutuhan administrasi.</p>
            </div>

            <div class="public-process-item" data-public-reveal>
                <div class="public-process-number">03</div>
                <h3>Kirim dokumen</h3>
                <p>Lengkapi dan unggah seluruh persyaratan yang dibutuhkan.</p>
            </div>

            <div class="public-process-item" data-public-reveal>
                <div class="public-process-number">04</div>
                <h3>Pantau proses</h3>
                <p>Lihat perkembangan permohonan melalui dashboard Anda.</p>
            </div>
        </div>
    </div>
</section>

<section class="public-section">
    <div class="public-container">
        <div class="public-faq">
            <div data-public-reveal>
                <div class="public-kicker">
                    FAQ
                </div>

                <h2 class="public-title-sm">
                    Pertanyaan yang sering ditanyakan.
                </h2>

                <p class="public-copy" style="margin-top:24px;max-width:430px;">
                    Temukan jawaban mengenai akun masyarakat, pengajuan layanan,
                    persyaratan dokumen, antrean, proses verifikasi, hingga hasil
                    pelayanan Kecamatan Panakkukang.
                </p>

                <div style="margin-top:32px;">
                    <a href="{{ url('/kontak') }}" class="public-button public-button-light">
                        Pertanyaan Lainnya
                    </a>
                </div>
            </div>

            <div class="public-faq-list" data-public-reveal>
                <div class="public-faq-item is-open" data-faq-item>
                    <button
                        type="button"
                        class="public-faq-question"
                        data-faq-button
                        aria-expanded="true"
                    >
                        <span>Apakah masyarakat harus memiliki akun?</span>

                        <span class="public-faq-icon">
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <div class="public-faq-content" data-faq-content>
                        <div class="public-faq-answer">
                            <p>
                                Ya. Akun masyarakat diperlukan untuk menggunakan
                                fitur pengajuan pelayanan secara online.
                            </p>

                            <p>
                                Setelah memiliki akun, masyarakat dapat memilih
                                layanan, mengisi formulir permohonan, mengunggah
                                dokumen persyaratan, melihat riwayat permohonan,
                                menerima notifikasi, dan memantau perkembangan
                                pelayanan melalui dashboard.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="public-faq-item" data-faq-item>
                    <button
                        type="button"
                        class="public-faq-question"
                        data-faq-button
                        aria-expanded="false"
                    >
                        <span>Apakah informasi layanan dapat dilihat tanpa login?</span>

                        <span class="public-faq-icon">
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <div class="public-faq-content" data-faq-content>
                        <div class="public-faq-answer">
                            <p>
                                Ya. Pengunjung dapat melihat daftar pelayanan
                                Kecamatan Panakkukang tanpa harus masuk ke dalam
                                sistem terlebih dahulu.
                            </p>

                            <p>
                                Informasi tersebut dapat digunakan untuk mengetahui
                                jenis pelayanan, seksi yang menangani, gambaran
                                proses pelayanan, dan informasi persyaratan sebelum
                                masyarakat membuat permohonan.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="public-faq-item" data-faq-item>
                    <button
                        type="button"
                        class="public-faq-question"
                        data-faq-button
                        aria-expanded="false"
                    >
                        <span>Bagaimana cara mengajukan pelayanan?</span>

                        <span class="public-faq-icon">
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <div class="public-faq-content" data-faq-content>
                        <div class="public-faq-answer">
                            <p>
                                Masyarakat terlebih dahulu masuk menggunakan akun
                                yang telah terdaftar, kemudian memilih jenis layanan
                                yang dibutuhkan melalui halaman layanan.
                            </p>

                            <p>
                                Selanjutnya masyarakat mengisi data permohonan,
                                melengkapi dokumen persyaratan, lalu mengirim
                                permohonan untuk diperiksa oleh petugas sesuai seksi
                                pelayanan terkait.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="public-faq-item" data-faq-item>
                    <button
                        type="button"
                        class="public-faq-question"
                        data-faq-button
                        aria-expanded="false"
                    >
                        <span>Bagaimana memantau status permohonan?</span>

                        <span class="public-faq-icon">
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <div class="public-faq-content" data-faq-content>
                        <div class="public-faq-answer">
                            <p>
                                Perkembangan permohonan dapat dilihat melalui
                                dashboard masyarakat pada menu Permohonan.
                            </p>

                            <p>
                                Sistem menampilkan status proses sehingga masyarakat
                                dapat mengetahui apakah permohonan masih menunggu
                                verifikasi, sedang diproses, membutuhkan revisi,
                                ditolak, atau telah selesai.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="public-faq-item" data-faq-item>
                    <button
                        type="button"
                        class="public-faq-question"
                        data-faq-button
                        aria-expanded="false"
                    >
                        <span>Bagaimana jika dokumen perlu diperbaiki?</span>

                        <span class="public-faq-icon">
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <div class="public-faq-content" data-faq-content>
                        <div class="public-faq-answer">
                            <p>
                                Jika petugas menemukan dokumen yang belum sesuai,
                                masyarakat akan mendapatkan informasi mengenai
                                dokumen yang harus diperbaiki.
                            </p>

                            <p>
                                Dokumen dapat diperbarui melalui halaman detail
                                permohonan. Setelah seluruh perbaikan selesai,
                                masyarakat dapat mengirim ulang permohonan agar
                                kembali diperiksa oleh petugas.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="public-faq-item" data-faq-item>
                    <button
                        type="button"
                        class="public-faq-question"
                        data-faq-button
                        aria-expanded="false"
                    >
                        <span>Apakah tersedia sistem antrean pelayanan?</span>

                        <span class="public-faq-icon">
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <div class="public-faq-content" data-faq-content>
                        <div class="public-faq-answer">
                            <p>
                                Untuk pelayanan yang menggunakan antrean, informasi
                                nomor dan status antrean dapat dilihat melalui menu
                                Antrean pada dashboard masyarakat.
                            </p>

                            <p>
                                Informasi tersebut membantu masyarakat mengetahui
                                posisi antrean dan perkembangan proses pelayanan
                                tanpa harus terus menanyakan kepada petugas.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="public-faq-item" data-faq-item>
                    <button
                        type="button"
                        class="public-faq-question"
                        data-faq-button
                        aria-expanded="false"
                    >
                        <span>Bagaimana mengetahui permohonan sudah selesai?</span>

                        <span class="public-faq-icon">
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <div class="public-faq-content" data-faq-content>
                        <div class="public-faq-answer">
                            <p>
                                Ketika proses pelayanan telah selesai, status
                                permohonan akan diperbarui pada dashboard dan
                                masyarakat dapat memperoleh pemberitahuan melalui
                                sistem.
                            </p>

                            <p>
                                Jika layanan menghasilkan dokumen digital, hasil
                                pelayanan dapat diakses atau diunduh melalui detail
                                permohonan sesuai dengan ketentuan layanan tersebut.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="public-section" style="padding-top:20px;">
    <div class="public-container">
        <div class="public-cta" data-public-reveal>
            <div class="public-cta-inner">
                <div>
                    <div class="public-kicker">
                        Mulai Sekarang
                    </div>

                    <h2 class="public-title-sm" style="max-width:650px;">
                        Urus pelayanan lebih mudah melalui satu sistem.
                    </h2>
                </div>

                @guest
                    <a href="{{ url('/register') }}" class="public-button">
                        Daftar Masyarakat
                    </a>
                @else
                    <a href="{{ url('/masyarakat') }}" class="public-button">
                        Buka Dashboard
                    </a>
[]                @endguest
            </div>
        </div>
    </div>
</section>
@endsection

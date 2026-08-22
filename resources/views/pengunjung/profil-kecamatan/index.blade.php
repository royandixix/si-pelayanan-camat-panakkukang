@extends('layouts.pengunjung')

@section('title', 'Profil Kecamatan - Kecamatan Panakkukang')

@section('content')
    <x-pengunjung.page-hero kicker="Tentang Kecamatan" title="Profil Kecamatan Panakkukang"
        description="Mengenal Kecamatan Panakkukang dan komitmennya dalam memberikan pelayanan pemerintahan kepada masyarakat." />

    <section class="public-section">
        <div class="public-container">
            <div class="public-split">
                <div class="public-visual" data-public-reveal>
                    <div class="public-visual-lime"></div>

                    <div class="public-visual-building">
                        <span class="public-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M3 21h18M6 21V8l6-4 6 4v13M9 12h6M9 16h6" />
                            </svg>
                        </span>

                        <h3 style="font-size:25px;margin:22px 0 7px;">
                            Panakkukang
                        </h3>

                        <p class="public-copy" style="margin:0;">
                            Kota Makassar
                        </p>
                    </div>
                </div>

                <div data-public-reveal>
                    <div class="public-kicker">
                        Sekilas Tentang
                    </div>

                    <h2 class="public-title-sm">
                        Pemerintahan yang hadir untuk masyarakat.
                    </h2>

                    <p class="public-copy" style="margin-top:24px;">
                        Kecamatan Panakkukang merupakan salah satu wilayah
                        administratif Kota Makassar yang menjalankan fungsi
                        pemerintahan, pelayanan publik, pembangunan, dan
                        pembinaan masyarakat.
                    </p>

                    <p class="public-copy">
                        Pemanfaatan teknologi informasi menjadi bagian dari upaya
                        meningkatkan pelayanan agar lebih mudah diakses,
                        transparan, dan terukur.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="public-section public-employee-section">
        <div class="public-container">
            <div class="public-employee-heading" data-public-reveal>
                <div>
                    <div class="public-kicker">
                        Aparatur Kecamatan
                    </div>

                    <h2 class="public-title-sm">
                        Pejabat dan Pegawai Kecamatan Panakkukang.
                    </h2>

                    <p class="public-copy public-employee-intro">
                        Mengenal aparatur Kecamatan Panakkukang yang menjalankan
                        tugas pemerintahan dan memberikan pelayanan kepada masyarakat.
                    </p>
                </div>

                <div class="public-employee-count">
                    <span>{{ $jumlahPegawai ?? 0 }}</span>
                    <small>Pegawai Aktif</small>
                </div>
            </div>

            <div class="public-employee-grid">
                @forelse($pegawai ?? [] as $item)
                    <article class="public-employee-card" data-public-reveal>
                        <div class="public-employee-photo">
                            @if ($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}">
                            @else
                                <div class="public-employee-placeholder">
                                    <span>
                                        {{ mb_strtoupper(mb_substr($item->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="public-employee-body">
                            <div class="public-employee-unit">
                                {{ $item->work_unit ?: 'Kecamatan Panakkukang' }}
                            </div>

                            <h3>
                                {{ $item->name }}
                            </h3>

                            <p>
                                {{ $item->position }}
                            </p>
                        </div>
                    </article>
                @empty
                    <div class="public-employee-empty">
                        Data pegawai belum tersedia.
                    </div>
                @endforelse
            </div>

            @if (($jumlahPegawai ?? 0) > 0)
                <div class="public-employee-more" data-public-reveal>
                    <a href="{{ route('pegawai.index') }}" class="public-button">
                        Lihat Semua Pegawai

                        <span>→</span>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <section class="public-section" style="background:#f7f7f5;">
        <div class="public-container">
            <div data-public-reveal>
                <div class="public-kicker">Arah Pelayanan</div>
                <h2 class="public-title-sm">Visi dan misi.</h2>
            </div>

            <div class="public-feature-grid" style="margin-top:55px;">
                <article class="public-card public-feature-card" data-public-reveal>
                    <span class="public-icon">01</span>

                    <h3>Visi</h3>

                    <p>
                        Mewujudkan pelayanan pemerintahan kecamatan yang
                        profesional, efektif, transparan, dan berorientasi
                        kepada masyarakat.
                    </p>
                </article>

                <article class="public-card public-feature-card" data-public-reveal>
                    <span class="public-icon" style="background:#fff;">02</span>

                    <h3>Pelayanan</h3>

                    <p>
                        Meningkatkan kualitas pelayanan administrasi dan
                        kemudahan akses informasi bagi masyarakat.
                    </p>
                </article>

                <article class="public-card public-feature-card" data-public-reveal>
                    <span class="public-icon" style="background:#e5edf4;color:#1f3550;">03</span>

                    <h3>Digitalisasi</h3>

                    <p>
                        Mengembangkan pemanfaatan teknologi informasi untuk
                        meningkatkan efektivitas pelayanan publik.
                    </p>
                </article>
            </div>
        </div>
    </section>

    <section class="public-section">
        <div class="public-container">
            <div data-public-reveal>
                <div class="public-kicker">Tugas & Fungsi</div>

                <h2 class="public-title-sm">
                    Peran Kecamatan Panakkukang.
                </h2>
            </div>

            <div class="public-service-grid">
                @foreach ([['Pemerintahan', 'Melaksanakan koordinasi penyelenggaraan pemerintahan di wilayah kecamatan.'], ['Administrasi', 'Memberikan pelayanan administrasi sesuai kewenangan dan kebutuhan masyarakat.'], ['Kemasyarakatan', 'Mendukung pemberdayaan, pembinaan, dan aktivitas sosial masyarakat.']] as $item)
                    <article class="public-card public-service-card" data-public-reveal>
                        <span class="public-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M8 12l3 3 5-6" />
                            </svg>
                        </span>

                        <h3>{{ $item[0] }}</h3>
                        <p>{{ $item[1] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="public-section" style="padding-top:10px;">
        <div class="public-container">
            <div class="public-cta" data-public-reveal>
                <div class="public-cta-inner">
                    <div>
                        <div class="public-kicker">Pelayanan Digital</div>

                        <h2 class="public-title-sm">
                            Temukan pelayanan yang Anda butuhkan.
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

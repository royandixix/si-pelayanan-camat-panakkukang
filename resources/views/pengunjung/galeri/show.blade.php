@extends('layouts.pengunjung')

@section('title', $gallery->title . ' - Galeri Kecamatan Panakkukang')

@section('content')
    @php
        $gambar = $gallery->image;

        if (
            str_starts_with($gambar, 'http://')
            || str_starts_with($gambar, 'https://')
        ) {
            $srcGambar = $gambar;
        } elseif (str_starts_with($gambar, 'img/')) {
            $srcGambar = asset($gambar);
        } else {
            $srcGambar = asset('storage/'.$gambar);
        }
    @endphp

    <style>
        .gallery-detail-page {
            padding: 72px 20px 90px;
        }

        .gallery-detail-container {
            width: min(1180px, 100%);
            margin: 0 auto;
        }

        .gallery-detail-breadcrumb {
            margin-bottom: 24px;
            font-size: 14px;
            color: #64748b;
        }

        .gallery-detail-breadcrumb a {
            color: #d89b2b;
            text-decoration: none;
            font-weight: 600;
        }

        .gallery-detail-card {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(340px, .8fr);
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 18px 50px rgba(15, 23, 42, .08);
        }

        .gallery-detail-image-wrap {
            background: #f1f5f9;
            min-height: 520px;
        }

        .gallery-detail-image {
            width: 100%;
            height: 100%;
            min-height: 520px;
            object-fit: cover;
            display: block;
        }

        .gallery-detail-content {
            padding: 48px 42px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .gallery-detail-label {
            display: inline-flex;
            width: max-content;
            padding: 7px 13px;
            margin-bottom: 18px;
            border-radius: 999px;
            background: #fff7e8;
            color: #b7791f;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .gallery-detail-title {
            margin: 0;
            color: #193753;
            font-size: clamp(30px, 4vw, 48px);
            line-height: 1.12;
            font-weight: 700;
        }

        .gallery-detail-date {
            margin-top: 16px;
            color: #94a3b8;
            font-size: 14px;
        }

        .gallery-detail-line {
            width: 54px;
            height: 3px;
            margin: 24px 0;
            border-radius: 999px;
            background: #d89b2b;
        }

        .gallery-detail-description {
            color: #475569;
            font-size: 16px;
            line-height: 1.9;
            white-space: pre-line;
        }

        .gallery-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: max-content;
            margin-top: 30px;
            padding: 12px 20px;
            border-radius: 999px;
            background: #193753;
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: .2s ease;
        }

        .gallery-back:hover {
            transform: translateY(-1px);
            opacity: .92;
        }

        .gallery-related {
            margin-top: 72px;
        }

        .gallery-related-heading {
            color: #193753;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .gallery-related-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .gallery-related-card {
            position: relative;
            display: block;
            height: 250px;
            overflow: hidden;
            border-radius: 18px;
            background: #e2e8f0;
            text-decoration: none;
        }

        .gallery-related-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .35s ease;
        }

        .gallery-related-card:hover img {
            transform: scale(1.05);
        }

        .gallery-related-overlay {
            position: absolute;
            inset: auto 0 0;
            padding: 45px 18px 18px;
            background: linear-gradient(
                transparent,
                rgba(15, 23, 42, .85)
            );
            color: white;
        }

        .gallery-related-overlay strong {
            display: block;
            font-size: 16px;
        }

        .gallery-related-overlay span {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            opacity: .8;
        }

        @media (max-width: 900px) {
            .gallery-detail-card {
                grid-template-columns: 1fr;
            }

            .gallery-detail-image-wrap,
            .gallery-detail-image {
                min-height: 360px;
            }

            .gallery-detail-content {
                padding: 34px 26px;
            }

            .gallery-related-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="gallery-detail-page">
        <div class="gallery-detail-container">
            <div class="gallery-detail-breadcrumb">
                <a href="{{ url('/') }}">Beranda</a>
                /
                <a href="{{ url('/galeri') }}">Galeri</a>
                /
                {{ $gallery->title }}
            </div>

            <div class="gallery-detail-card">
                <div class="gallery-detail-image-wrap">
                    <img
                        src="{{ $srcGambar }}"
                        alt="{{ $gallery->title }}"
                        class="gallery-detail-image"
                    >
                </div>

                <div class="gallery-detail-content">
                    @if ($gallery->category)
                        <div class="gallery-detail-label">
                            {{ $gallery->category }}
                        </div>
                    @endif

                    <h1 class="gallery-detail-title">
                        {{ $gallery->title }}
                    </h1>

                    <div class="gallery-detail-date">
                        Dipublikasikan
                        {{ $gallery->created_at?->translatedFormat('d F Y') }}
                    </div>

                    <div class="gallery-detail-line"></div>

                    <div class="gallery-detail-description">
                        {{ $gallery->description ?: 'Belum ada deskripsi untuk kegiatan ini.' }}
                    </div>

                    <a
                        href="{{ url('/galeri') }}"
                        class="gallery-back"
                    >
                        ← Kembali ke Galeri
                    </a>
                </div>
            </div>

            @if ($galeriLainnya->isNotEmpty())
                <div class="gallery-related">
                    <h2 class="gallery-related-heading">
                        Galeri Lainnya
                    </h2>

                    <div class="gallery-related-grid">
                        @foreach ($galeriLainnya as $item)
                            @php
                                if (
                                    str_starts_with($item->image, 'http://')
                                    || str_starts_with($item->image, 'https://')
                                ) {
                                    $gambarItem = $item->image;
                                } elseif (
                                    str_starts_with($item->image, 'img/')
                                ) {
                                    $gambarItem = asset($item->image);
                                } else {
                                    $gambarItem = asset(
                                        'storage/'.$item->image
                                    );
                                }
                            @endphp

                            <a
                                href="{{ route('pengunjung.galeri.show', $item) }}"
                                class="gallery-related-card"
                            >
                                <img
                                    src="{{ $gambarItem }}"
                                    alt="{{ $item->title }}"
                                >

                                <div class="gallery-related-overlay">
                                    <strong>
                                        {{ $item->title }}
                                    </strong>

                                    @if ($item->category)
                                        <span>
                                            {{ $item->category }}
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@extends('layouts.pengunjung')

@section('title', $berita->title . ' - Kecamatan Panakkukang')

@section('content')
@php
    $gambar = $berita->image;

    if (
        $gambar
        && (
            str_starts_with($gambar, 'http://')
            || str_starts_with($gambar, 'https://')
        )
    ) {
        $srcGambar = $gambar;
    } elseif (
        $gambar
        && str_starts_with($gambar, 'img/')
    ) {
        $srcGambar = asset($gambar);
    } elseif ($gambar) {
        $srcGambar = asset('storage/'.$gambar);
    } else {
        $srcGambar = null;
    }
@endphp

<style>
    .news-detail-wrap {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(330px, .9fr);
        gap: 48px;
        align-items: start;
    }

    .news-detail-image {
        width: 100%;
        min-height: 480px;
        max-height: 650px;
        object-fit: cover;
        border-radius: 24px;
        display: block;
        background: #eef2f6;
    }

    .news-detail-title {
        margin: 14px 0 12px;
        color: #193753;
        font-size: clamp(30px, 4vw, 44px);
        line-height: 1.15;
    }

    .news-detail-meta {
        color: #94a3b8;
        font-size: 14px;
    }

    .news-detail-excerpt {
        margin-top: 25px;
        color: #475569;
        font-size: 17px;
        font-weight: 600;
        line-height: 1.8;
    }

    .news-detail-content {
        margin-top: 24px;
        color: #475569;
        font-size: 16px;
        line-height: 1.9;
        white-space: pre-line;
    }

    .news-detail-back {
        display: inline-flex;
        margin-top: 30px;
        padding: 12px 20px;
        border-radius: 999px;
        background: #193753;
        color: white;
        text-decoration: none;
        font-weight: 600;
    }

    .news-related {
        margin-top: 80px;
    }

    .news-related-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    @media (max-width: 900px) {
        .news-detail-wrap {
            grid-template-columns: 1fr;
        }

        .news-detail-image {
            min-height: 320px;
        }
    }
</style>

<x-pengunjung.page-hero
    kicker="{{ $berita->category ?: 'Berita Kecamatan' }}"
    title="Detail Berita"
    description="Informasi dan kegiatan terbaru Kecamatan Panakkukang."
/>

<section class="public-section">
    <div class="public-container">
        <div class="news-detail-wrap">
            <div>
                @if($srcGambar)
                    <img
                        src="{{ $srcGambar }}"
                        alt="{{ $berita->title }}"
                        class="news-detail-image"
                    >
                @else
                    <div class="news-detail-image"></div>
                @endif
            </div>

            <article>
                <div class="public-kicker">
                    {{ $berita->category ?: 'Informasi' }}
                </div>

                <h1 class="news-detail-title">
                    {{ $berita->title }}
                </h1>

                <div class="news-detail-meta">
                    Dipublikasikan
                    {{ $berita->published_at?->translatedFormat('d F Y H:i') }}
                </div>

                @if($berita->excerpt)
                    <div class="news-detail-excerpt">
                        {{ $berita->excerpt }}
                    </div>
                @endif

                <div class="news-detail-content">
                    {{
                        $berita->content
                        ?: $berita->excerpt
                        ?: 'Informasi lengkap belum tersedia.'
                    }}
                </div>

                <a
                    href="{{ route('berita') }}"
                    class="news-detail-back"
                >
                    ← Kembali ke Berita
                </a>
            </article>
        </div>

        @if($beritaLainnya->isNotEmpty())
            <div class="news-related">
                <div class="public-kicker">
                    Berita Lainnya
                </div>

                <h2 class="public-title-sm">
                    Informasi terbaru lainnya.
                </h2>

                <div
                    class="public-news-grid"
                    style="margin-top:35px;"
                >
                    @foreach($beritaLainnya as $item)
                        <a
                            href="{{ route('berita.show', $item) }}"
                            class="public-card public-news-card news-related-link"
                        >
                            <div class="public-news-body">
                                <div class="public-news-date">
                                    {{ $item->published_at?->translatedFormat('d F Y') }}
                                </div>

                                <h3>{{ $item->title }}</h3>

                                <p
                                    class="public-copy"
                                    style="font-size:12px;"
                                >
                                    {{
                                        \Illuminate\Support\Str::limit(
                                            $item->excerpt
                                            ?: $item->content,
                                            130
                                        )
                                    }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@extends('layouts.pengunjung')

@section('title', $berita->title . ' - Kecamatan Panakkukang')

@section('content')
@php
    if ($berita->image) {
        if (
            str_starts_with($berita->image, 'http://')
            || str_starts_with($berita->image, 'https://')
        ) {
            $srcGambar = $berita->image;
        } elseif (
            str_starts_with($berita->image, 'img/')
        ) {
            $srcGambar = asset($berita->image);
        } else {
            $srcGambar = asset(
                'storage/'.$berita->image
            );
        }
    } else {
        $srcGambar = null;
    }
@endphp

<x-pengunjung.page-hero
    kicker="{{ $berita->category ?: 'Berita Kecamatan' }}"
    title="{{ $berita->title }}"
    description="{{ $berita->excerpt ?: 'Informasi Kecamatan Panakkukang.' }}"
/>

<section class="public-section">
    <div class="public-container">
        <div
            style="
                display:grid;
                grid-template-columns:minmax(0,1.1fr) minmax(320px,.9fr);
                gap:45px;
                align-items:start;
            "
        >
            <div>
                @if($srcGambar)
                    <img
                        src="{{ $srcGambar }}"
                        alt="{{ $berita->title }}"
                        style="
                            width:100%;
                            min-height:400px;
                            max-height:620px;
                            object-fit:cover;
                            border-radius:24px;
                            display:block;
                        "
                    >
                @else
                    <div
                        style="
                            min-height:420px;
                            border-radius:24px;
                            background:#edf2f6;
                            display:flex;
                            justify-content:center;
                            align-items:center;
                        "
                    >
                        <span class="public-icon">
                            <svg
                                width="50"
                                height="50"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                            >
                                <path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5"/>
                            </svg>
                        </span>
                    </div>
                @endif
            </div>

            <article>
                <div class="public-kicker">
                    {{ $berita->category ?: 'Informasi' }}
                </div>

                <h1
                    style="
                        margin:15px 0;
                        color:#193753;
                        font-size:clamp(30px,4vw,44px);
                        line-height:1.18;
                    "
                >
                    {{ $berita->title }}
                </h1>

                <div
                    style="
                        margin-bottom:24px;
                        color:#94a3b8;
                        font-size:14px;
                    "
                >
                    Dipublikasikan
                    {{ $berita->published_at?->translatedFormat('d F Y H:i') }}
                </div>

                @if($berita->excerpt)
                    <p
                        style="
                            font-size:17px;
                            line-height:1.8;
                            font-weight:600;
                            color:#475569;
                        "
                    >
                        {{ $berita->excerpt }}
                    </p>
                @endif

                <div
                    style="
                        margin-top:24px;
                        color:#475569;
                        font-size:16px;
                        line-height:1.95;
                        white-space:pre-line;
                    "
                >{{ $berita->content }}</div>

                <a
                    href="{{ route('berita') }}"
                    style="
                        display:inline-flex;
                        margin-top:32px;
                        padding:12px 20px;
                        border-radius:999px;
                        background:#193753;
                        color:#fff;
                        text-decoration:none;
                        font-weight:600;
                    "
                >
                    ← Kembali ke Berita
                </a>
            </article>
        </div>

        @if($beritaLainnya->isNotEmpty())
            <div style="margin-top:80px;">
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
                            class="public-card public-news-card"
                            style="
                                display:block;
                                text-decoration:none;
                                color:inherit;
                            "
                        >
                            <div class="public-news-body">
                                <div class="public-news-date">
                                    {{ $item->published_at?->translatedFormat('d F Y') }}
                                </div>

                                <h3>
                                    {{ $item->title }}
                                </h3>

                                <p class="public-copy">
                                    {{ \Illuminate\Support\Str::limit(
                                        $item->excerpt
                                        ?: $item->content,
                                        130
                                    ) }}
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

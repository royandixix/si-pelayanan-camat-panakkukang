@extends('layouts.pengunjung')

@section('title', 'Berita & Informasi - Kecamatan Panakkukang')

@section('content')
<style>
    .public-news-link {
        display: block;
        color: inherit;
        text-decoration: none;
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .public-news-link:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 40px rgba(15, 23, 42, .12);
    }

    .public-news-link img {
        transition: transform .35s ease;
    }

    .public-news-link:hover img {
        transform: scale(1.035);
    }
</style>

<x-pengunjung.page-hero
    kicker="Informasi Kecamatan"
    title="Berita & Informasi"
    description="Ikuti informasi terbaru mengenai kegiatan pemerintahan, pelayanan masyarakat, pengumuman, dan agenda Kecamatan Panakkukang."
/>

<section class="public-section">
    <div class="public-container">
        <div data-public-reveal>
            <div class="public-kicker">
                Informasi Terkini
            </div>

            <h2 class="public-title-sm">
                Kabar terbaru Kecamatan Panakkukang.
            </h2>
        </div>

        <div class="public-news-grid" style="margin-top:55px;">
            @forelse($berita as $item)
                @php
                    $gambar = $item->image;

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

                <a
                    href="{{ route('berita.show', $item) }}"
                    class="public-card public-news-card public-news-link"
                    data-public-reveal
                >
                    <div class="public-news-media">
                        @if($srcGambar)
                            <img
                                src="{{ $srcGambar }}"
                                alt="{{ $item->title }}"
                            >
                        @else
                            <span class="public-icon">
                                <svg
                                    width="25"
                                    height="25"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5"/>
                                </svg>
                            </span>
                        @endif
                    </div>

                    <div class="public-news-body">
                        <div class="public-news-date">
                            {{ $item->published_at?->translatedFormat('d F Y') }}
                        </div>

                        @if($item->category)
                            <div
                                style="
                                    margin-top:8px;
                                    font-size:11px;
                                    font-weight:700;
                                    color:#b7791f;
                                    text-transform:uppercase;
                                    letter-spacing:.08em;
                                "
                            >
                                {{ $item->category }}
                            </div>
                        @endif

                        <h3>{{ $item->title }}</h3>

                        <p
                            class="public-copy"
                            style="font-size:12px;"
                        >
                            {{
                                \Illuminate\Support\Str::limit(
                                    strip_tags(
                                        $item->excerpt
                                        ?: $item->content
                                        ?: 'Informasi terbaru Kecamatan Panakkukang.'
                                    ),
                                    145
                                )
                            }}
                        </p>
                    </div>
                </a>
            @empty
                <div
                    class="public-card"
                    style="
                        grid-column:1/-1;
                        text-align:center;
                        padding:60px 30px;
                    "
                >
                    <div class="public-icon" style="margin:0 auto 18px;">
                        <svg
                            width="28"
                            height="28"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5"/>
                        </svg>
                    </div>

                    <h3>Belum Ada Berita</h3>

                    <p class="public-copy">
                        Berita dan informasi akan ditampilkan setelah
                        ditambahkan oleh Super Admin.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

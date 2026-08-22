@extends('layouts.pengunjung')

@section('title', 'Berita & Informasi - Kecamatan Panakkukang')

@section('content')
<x-pengunjung.page-hero
    kicker="Informasi Kecamatan"
    title="Berita & Informasi"
    description="Ikuti informasi terbaru mengenai kegiatan pemerintahan, pelayanan masyarakat, pengumuman, dan agenda Kecamatan Panakkukang."
/>

<section class="public-section">
    <div class="public-container">
        @php
            $daftarBerita = collect($berita ?? []);
        @endphp

        <div data-public-reveal>
            <div class="public-kicker">
                Informasi Terkini
            </div>

            <h2 class="public-title-sm">
                Kabar terbaru Kecamatan Panakkukang.
            </h2>
        </div>

        <div class="public-news-grid" style="margin-top:55px;">
            @forelse($daftarBerita as $item)
                @php
                    $judul = data_get($item, 'title', data_get($item, 'judul', 'Informasi Kecamatan Panakkukang'));
                    $isi = data_get($item, 'excerpt', data_get($item, 'ringkasan', data_get($item, 'content', data_get($item, 'isi', 'Informasi terbaru Kecamatan Panakkukang.'))));
                    $gambar = data_get($item, 'image', data_get($item, 'gambar'));
                    $tanggal = data_get($item, 'published_at', data_get($item, 'tanggal', data_get($item, 'created_at')));
                @endphp

                <article class="public-card public-news-card" data-public-reveal>
                    <div class="public-news-media">
                        @if($gambar)
                            <img
                                src="{{ str_starts_with($gambar, 'http') ? $gambar : asset('storage/'.$gambar) }}"
                                alt="{{ $judul }}"
                            >
                        @else
                            <span class="public-icon">
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5"/>
                                </svg>
                            </span>
                        @endif
                    </div>

                    <div class="public-news-body">
                        <div class="public-news-date">
                            {{ $tanggal ? \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') : 'Informasi Kecamatan' }}
                        </div>

                        <h3>{{ $judul }}</h3>

                        <p class="public-copy" style="font-size:12px;">
                            {{ \Illuminate\Support\Str::limit(strip_tags($isi), 145) }}
                        </p>
                    </div>
                </article>
            @empty
                @for($i = 1; $i <= 3; $i++)
                    <article class="public-card public-news-card" data-public-reveal>
                        <div class="public-news-media">
                            <span class="public-icon">
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5"/>
                                </svg>
                            </span>
                        </div>

                        <div class="public-news-body">
                            <div class="public-news-date">
                                Informasi Kecamatan
                            </div>

                            <h3>Informasi Kecamatan Panakkukang</h3>

                            <p class="public-copy" style="font-size:12px;">
                                Informasi terbaru mengenai kegiatan dan pelayanan
                                akan ditampilkan pada halaman ini.
                            </p>
                        </div>
                    </article>
                @endfor
            @endforelse
        </div>
    </div>
</section>
@endsection

@extends('layouts.pengunjung')

@section('title', 'Galeri - Kecamatan Panakkukang')

@section('content')
<x-pengunjung.page-hero
    kicker="Dokumentasi"
    title="Galeri Kecamatan"
    description="Dokumentasi kegiatan pemerintahan, pelayanan publik, dan aktivitas masyarakat di Kecamatan Panakkukang."
/>

<section class="public-section">
    <div class="public-container">
        <div data-public-reveal>
            <div class="public-kicker">
                Galeri Kegiatan
            </div>

            <h2 class="public-title-sm">
                Melihat aktivitas Kecamatan Panakkukang.
            </h2>
        </div>

        <div class="public-gallery-grid" style="margin-top:55px;">
            @forelse(collect($galeri ?? []) as $item)
                @php
                    $judul = data_get($item, 'title', data_get($item, 'judul', data_get($item, 'name', 'Kegiatan Kecamatan Panakkukang')));
                    $gambar = data_get($item, 'image', data_get($item, 'gambar', data_get($item, 'photo', data_get($item, 'foto'))));
                @endphp

                <article class="public-gallery-item" data-public-reveal>
                    @if($gambar)
                        <img
                            src="{{ str_starts_with($gambar, 'http') ? $gambar : asset('storage/'.$gambar) }}"
                            alt="{{ $judul }}"
                        >
                    @else
                        <div class="public-gallery-fallback">
                            <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M4 5h16v14H4z"/>
                                <circle cx="9" cy="10" r="2"/>
                                <path d="m4 17 5-5 4 4 2-2 5 4"/>
                            </svg>
                        </div>
                    @endif

                    <div class="public-gallery-overlay">
                        <h3 style="margin:0;font-size:19px;">
                            {{ $judul }}
                        </h3>
                    </div>
                </article>
            @empty
                @for($i = 1; $i <= 5; $i++)
                    <article class="public-gallery-item" data-public-reveal>
                        <div
                            class="public-gallery-fallback"
                            style="background:{{ $i % 2 === 0 ? '#edf1f5' : '#e5edf4' }};"
                        >
                            <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M4 5h16v14H4z"/>
                                <circle cx="9" cy="10" r="2"/>
                                <path d="m4 17 5-5 4 4 2-2 5 4"/>
                            </svg>
                        </div>

                        <div class="public-gallery-overlay">
                            <h3 style="margin:0;font-size:19px;">
                                Kegiatan Kecamatan Panakkukang
                            </h3>
                        </div>
                    </article>
                @endfor
            @endforelse
        </div>
    </div>
</section>
@endsection

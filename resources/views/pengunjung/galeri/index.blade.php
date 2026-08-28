@extends('layouts.pengunjung')

@section('title', 'Galeri - Kecamatan Panakkukang')

@section('content')
<style>
    .public-gallery-item {
        cursor: pointer;
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .public-gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 40px rgba(15, 23, 42, .15);
    }

    .public-gallery-item img {
        transition: transform .35s ease;
    }

    .public-gallery-item:hover img {
        transform: scale(1.035);
    }
</style>
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
                    $judul = data_get(
                        $item,
                        'title',
                        data_get(
                            $item,
                            'judul',
                            data_get(
                                $item,
                                'name',
                                'Kegiatan Kecamatan Panakkukang'
                            )
                        )
                    );

                    $gambar = data_get(
                        $item,
                        'image',
                        data_get(
                            $item,
                            'gambar',
                            data_get(
                                $item,
                                'photo',
                                data_get($item, 'foto')
                            )
                        )
                    );

                    $srcGambar = null;

                    if ($gambar) {
                        if (
                            str_starts_with($gambar, 'http://')
                            || str_starts_with($gambar, 'https://')
                        ) {
                            $srcGambar = $gambar;
                        } elseif (
                            str_starts_with($gambar, 'img/')
                        ) {
                            $srcGambar = asset($gambar);
                        } else {
                            $srcGambar = asset(
                                'storage/'.$gambar
                            );
                        }
                    }
                @endphp

                <a
                    href="{{ route('pengunjung.galeri.show', $item) }}"
                    class="public-gallery-item"
                    data-public-reveal
                    style="display:block;text-decoration:none;color:inherit;"
                >
                    @if($gambar)
                        <img
                            src="{{ $srcGambar }}"
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
                </a>
            @empty
                <div
                    class="public-gallery-fallback"
                    style="grid-column:1/-1;padding:50px;text-align:center;border-radius:20px;background:#f3f6f9;"
                >
                    <svg
                        width="52"
                        height="52"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        style="margin:0 auto 16px;"
                    >
                        <path d="M4 5h16v14H4z"/>
                        <circle cx="9" cy="10" r="2"/>
                        <path d="m4 17 5-5 4 4 2-2 5 4"/>
                    </svg>

                    <div style="font-weight:700;font-size:18px;">
                        Belum Ada Galeri
                    </div>

                    <div style="margin-top:7px;color:#64748b;">
                        Foto kegiatan akan ditampilkan setelah ditambahkan oleh Super Admin.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

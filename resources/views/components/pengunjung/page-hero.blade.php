@props([
    'kicker',
    'title',
    'description',
])

<section class="public-page-hero">
    <div class="public-container">
        <div class="public-page-hero-inner" data-public-reveal>
            <div class="public-page-hero-content">
                <div class="public-kicker">
                    {{ $kicker }}
                </div>

                <h1 class="public-title">
                    {{ $title }}
                </h1>

                <p class="public-copy" style="max-width:620px;margin-top:22px;">
                    {{ $description }}
                </p>

                <div class="public-breadcrumb">
                    <a href="{{ url('/') }}">Beranda</a>
                    <span>•</span>
                    <span>{{ $title }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

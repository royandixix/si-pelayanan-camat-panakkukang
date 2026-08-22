@props([
    'eyebrow' => 'Kecamatan Panakkukang',
    'title',
    'description' => null,
    'current' => null,
])

<section class="public-page-header">
    <div class="public-page-header-pattern"></div>
    <div class="public-container relative py-20 sm:py-24">
        <div class="max-w-3xl" data-reveal>
            <span class="section-eyebrow-light">{{ $eyebrow }}</span>
            <h1 class="mt-4 text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $title }}</h1>
            @if($description)
                <p class="mt-5 max-w-2xl text-base leading-8 text-emerald-50/70 sm:text-lg">{{ $description }}</p>
            @endif
            <div class="mt-7 flex items-center gap-2 text-sm font-semibold text-emerald-100/60">
                <a href="{{ route('beranda') }}" class="transition hover:text-amber-400">Beranda</a>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-white">{{ $current ?? $title }}</span>
            </div>
        </div>
    </div>
</section>

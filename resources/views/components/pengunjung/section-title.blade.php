@props([
    'eyebrow',
    'title',
    'description' => null,
    'center' => false,
    'light' => false,
])

<div class="{{ $center ? 'mx-auto max-w-3xl text-center' : 'max-w-3xl' }}" data-reveal>
    <span class="{{ $light ? 'section-eyebrow-light' : 'section-eyebrow' }}">{{ $eyebrow }}</span>
    <h2 class="mt-3 text-3xl font-semibold leading-tight tracking-tight {{ $light ? 'text-white' : 'text-emerald-950' }} sm:text-4xl lg:text-[42px]">{{ $title }}</h2>
    @if($description)
        <p class="mt-5 leading-8 {{ $light ? 'text-emerald-50/70' : 'text-slate-500' }}">{{ $description }}</p>
    @endif
</div>

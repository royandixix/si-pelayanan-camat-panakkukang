<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kecamatan Panakkukang')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="public-site">

@include('partials.pengunjung.navbar')

<main>
    @yield('content')
</main>

@include('partials.pengunjung.footer')

<button
    type="button"
    class="public-back-top"
    data-public-back-top
    aria-label="Kembali ke atas"
>
    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="m18 15-6-6-6 6"/>
    </svg>
</button>

@stack('scripts')

</body>
</html>

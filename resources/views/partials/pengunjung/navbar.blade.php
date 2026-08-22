<header class="public-nav">
    <div class="public-container">
        <div class="public-nav-inner">
            <a href="{{ url('/') }}" class="public-brand">
                <span class="public-brand-mark">KP</span>

                <span>
                    <span class="public-brand-title">
                        Kecamatan Panakkukang
                    </span>

                    <span class="public-brand-subtitle">
                        Pelayanan Masyarakat
                    </span>
                </span>
            </a>

            <nav class="public-nav-links">
                <a href="{{ url('/') }}" class="public-nav-link {{ request()->is('/') ? 'is-active' : '' }}">
                    Beranda
                </a>

                <a href="{{ url('/profil') }}" class="public-nav-link {{ request()->is('profil') ? 'is-active' : '' }}">
                    Profil
                </a>

                <a href="{{ url('/layanan') }}" class="public-nav-link {{ request()->is('layanan*') ? 'is-active' : '' }}">
                    Layanan
                </a>

                <a href="{{ url('/berita') }}" class="public-nav-link {{ request()->is('berita*') ? 'is-active' : '' }}">
                    Informasi
                </a>

                <a href="{{ url('/galeri') }}" class="public-nav-link {{ request()->is('galeri*') ? 'is-active' : '' }}">
                    Galeri
                </a>

                <a href="{{ url('/kontak') }}" class="public-nav-link {{ request()->is('kontak') ? 'is-active' : '' }}">
                    Kontak
                </a>
            </nav>

            <div class="public-nav-desktop-actions">
                @guest
                    <a href="{{ url('/login') }}" class="public-button">
                        Masuk
                    </a>
                @else
                    <a href="{{ url('/masyarakat') }}" class="public-button">
                        Dashboard
                    </a>
                @endguest
            </div>

            <button
                type="button"
                class="public-menu-button"
                data-public-menu-button
                aria-expanded="false"
                aria-label="Buka menu"
            >
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </button>
        </div>

        <div class="public-mobile-menu" data-public-mobile-menu>
            <div class="public-mobile-menu-inner">
                <a href="{{ url('/') }}">Beranda</a>
                <a href="{{ url('/profil') }}">Profil</a>
                <a href="{{ url('/layanan') }}">Layanan</a>
                <a href="{{ url('/berita') }}">Informasi</a>
                <a href="{{ url('/galeri') }}">Galeri</a>
                <a href="{{ url('/kontak') }}">Kontak</a>

                @guest
                    <a href="{{ url('/login') }}" class="public-button">
                        Masuk
                    </a>
                @else
                    <a href="{{ url('/masyarakat') }}" class="public-button">
                        Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>
</header>

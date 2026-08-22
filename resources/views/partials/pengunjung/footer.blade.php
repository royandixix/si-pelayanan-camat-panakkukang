<footer class="public-footer">
    <div class="public-container">
        <div class="public-footer-grid">
            <div>
                <a href="{{ url('/') }}" class="public-brand">
                    <span class="public-brand-mark" style="background:#ffffff;color:#1f3550;">KP</span>

                    <span>
                        <span class="public-brand-title" style="color:#fff;">
                            Kecamatan Panakkukang
                        </span>

                        <span class="public-brand-subtitle">
                            Kota Makassar
                        </span>
                    </span>
                </a>

                <p class="public-footer-copy">
                    Sistem Informasi Pelayanan Masyarakat Kecamatan Panakkukang
                    membantu masyarakat memperoleh informasi dan mengakses
                    layanan secara lebih mudah, cepat, dan transparan.
                </p>
            </div>

            <div>
                <h3>Menu</h3>

                <div class="public-footer-links">
                    <a href="{{ url('/') }}">Beranda</a>
                    <a href="{{ url('/profil') }}">Profil Kecamatan</a>
                    <a href="{{ url('/layanan') }}">Layanan</a>
                    <a href="{{ url('/berita') }}">Informasi</a>
                    <a href="{{ url('/galeri') }}">Galeri</a>
                </div>
            </div>

            <div>
                <h3>Pelayanan</h3>

                <div class="public-footer-links">
                    <a href="{{ url('/kontak') }}">Kontak</a>
                    <a href="{{ url('/login') }}">Masuk</a>
                    <a href="{{ url('/register') }}">Daftar Masyarakat</a>
                </div>
            </div>
        </div>

        <div class="public-footer-bottom">
            <span>© {{ date('Y') }} Kecamatan Panakkukang</span>
            <span>Sistem Informasi Pelayanan Masyarakat</span>
        </div>
    </div>
</footer>

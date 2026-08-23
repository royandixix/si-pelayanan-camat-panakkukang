<?php

use App\Http\Controllers\Autentikasi\LoginMasyarakatController;
use App\Http\Controllers\Autentikasi\RegistrasiMasyarakatController;
use App\Http\Controllers\Masyarakat\AntreanController;
use App\Http\Controllers\Masyarakat\BuktiController;
use App\Http\Controllers\Masyarakat\DasborController;
use App\Http\Controllers\Masyarakat\DokumenPermohonanController;
use App\Http\Controllers\Masyarakat\HasilPelayananController;
use App\Http\Controllers\Masyarakat\LayananController as LayananMasyarakatController;
use App\Http\Controllers\Masyarakat\NotifikasiController;
use App\Http\Controllers\Masyarakat\PermohonanController;
use App\Http\Controllers\Masyarakat\ProfilController;
use App\Http\Controllers\Masyarakat\RevisiDokumenController;
use App\Http\Controllers\Pengunjung\BerandaController;
use App\Http\Controllers\Pengunjung\BeritaController;
use App\Http\Controllers\Pengunjung\GaleriController;
use App\Http\Controllers\Pengunjung\KontakController;
use App\Http\Controllers\Pengunjung\LayananController as LayananPengunjungController;
use App\Http\Controllers\Pengunjung\ProfilKecamatanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pengunjung\PegawaiController;

Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/profil', [ProfilKecamatanController::class, 'index'])->name('profil-kecamatan');
Route::get('/profil/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
Route::get('/layanan', [LayananPengunjungController::class, 'index'])->name('layanan');
Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginMasyarakatController::class, 'create'])->name('login');
    Route::post('/login', [LoginMasyarakatController::class, 'store'])->name('login.store');
    Route::get('/register', [RegistrasiMasyarakatController::class, 'create'])->name('register');
    Route::post('/register', [RegistrasiMasyarakatController::class, 'store'])->name('register.store');
});

Route::middleware('auth')
    ->prefix('masyarakat')
    ->name('masyarakat.')
    ->group(function (): void {
        Route::get('/', [DasborController::class, 'index'])->name('dashboard');
        Route::get('/layanan', [LayananMasyarakatController::class, 'index'])->name('layanan.index');
        Route::get('/layanan/{layanan:slug}', [LayananMasyarakatController::class, 'show'])->name('layanan.show');
        Route::get('/permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
        Route::get('/permohonan/buat/{layanan:slug}', [PermohonanController::class, 'create'])->name('permohonan.create');
        Route::post('/permohonan/buat/{layanan:slug}', [PermohonanController::class, 'store'])->name('permohonan.store');
        Route::get('/permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('permohonan.show');
        Route::get('/permohonan/{permohonan}/bukti', [BuktiController::class, 'show'])->name('permohonan.bukti.show');
        Route::get('/permohonan/{permohonan}/bukti/unduh', [BuktiController::class, 'download'])->name('permohonan.bukti.download');
        Route::get('/permohonan/{permohonan}/hasil/unduh', [HasilPelayananController::class, 'download'])->name('permohonan.hasil.download');
        Route::get('/permohonan/{permohonan}/dokumen/{dokumen}/unduh', [DokumenPermohonanController::class, 'download'])->name('permohonan.dokumen.download');
        Route::patch('/permohonan/{permohonan}/dokumen/{dokumen}/revisi', [RevisiDokumenController::class, 'update'])->name('permohonan.dokumen.update');
        Route::post('/permohonan/{permohonan}/kirim-ulang', [RevisiDokumenController::class, 'kirimUlang'])->name('permohonan.kirim-ulang');
        Route::get('/antrean', [AntreanController::class, 'index'])->name('antrean.index');
        Route::get('/antrean/{antrean}', [AntreanController::class, 'show'])->name('antrean.show');
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::patch('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('notifikasi.baca-semua');
        Route::post('/notifikasi/{notifikasi}/buka', [NotifikasiController::class, 'buka'])->name('notifikasi.buka');
        Route::patch('/notifikasi/{notifikasi}/baca', [NotifikasiController::class, 'baca'])->name('notifikasi.baca');
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
        Route::patch('/profil', [ProfilController::class, 'update'])->name('profil.update');
        Route::get('/profil/kata-sandi', [ProfilController::class, 'kataSandi'])->name('profil.kata-sandi');
        Route::patch('/profil/kata-sandi', [ProfilController::class, 'updateKataSandi'])->name('profil.kata-sandi.update');
    });

Route::post('/logout', [LoginMasyarakatController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

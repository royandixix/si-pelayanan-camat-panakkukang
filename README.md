# Sistem Informasi Pelayanan Masyarakat Kantor Camat Panakkukang

> **Perancangan Sistem Informasi Pelayanan Masyarakat dengan Klasterisasi Jenis Layanan Menggunakan Metode K-Means untuk Optimasi Distribusi Pegawai**
>
> Proposal Skripsi — Program Studi Sistem Informasi, Universitas Dipa Makassar (2026)
> Oleh: **Ibnu Hajar Arsalli (221065)** & **Ardianto Raba (221157)**

Sistem informasi pelayanan publik berbasis web untuk Kantor Camat Panakkukang, Kota Makassar. Sistem mencakup pelayanan online dari **lima seksi**, sistem **antrean digital FIFO**, serta **klasterisasi K-Means** terhadap volume layanan per seksi sebagai dasar **rekomendasi distribusi pegawai** bagi pimpinan.

Repository ini berisi **back-end dan panel admin** (Laravel + Filament) yang menyediakan basis data MySQL, logika bisnis, serta dashboard admin/pimpinan.

## 🎯 Latar Belakang Singkat

Seluruh pelayanan di Kantor Camat Panakkukang selama ini masih konvensional: masyarakat harus datang langsung, pencatatan berbasis kertas, dan antrean menumpuk. Di sisi lain, pimpinan tidak memiliki data kuantitatif untuk menilai beban kerja tiap seksi, sehingga distribusi pegawai bersifat subjektif. Sistem ini menjawab dua masalah tersebut.

## 👥 Aktor Sistem

| Aktor | Peran Utama |
|---|---|
| **Masyarakat** | Registrasi & login, mengajukan permohonan online, mengunggah dokumen, cek status & riwayat, mengambil nomor antrean digital, mencetak bukti |
| **Admin Seksi** | Verifikasi permohonan, meminta perbaikan dokumen, menyetujui/menolak, memperbarui status, mengelola & memanggil antrean, mencetak laporan |
| **Super Admin** | Kelola admin & reset password, kelola 5 seksi & penempatan admin, kelola jenis layanan/persyaratan/formulir, monitoring seluruh permohonan & antrean |
| **Camat / Pimpinan** | Dashboard volume layanan per seksi, grafik bulanan, perbandingan beban kerja 5 seksi, hasil klasterisasi K-Means, rekomendasi distribusi pegawai, unduh laporan |

## 🗂️ Sepuluh Jenis Layanan (5 Seksi)

| No | Jenis Layanan | Seksi |
|---|---|---|
| 1 | Keterangan Ahli Waris | Pemberdayaan Masyarakat & Kesejahteraan Sosial |
| 2 | Izin Meneliti | Pemerintahan |
| 3 | Konsultasi Pertanahan | Pemerintahan |
| 4 | Konsultasi PPAT | Pemerintahan |
| 5 | Pengaduan Masyarakat | Ketenteraman & Ketertiban Umum |
| 6 | Rekomendasi Kegiatan | Seksi terkait |
| 7 | Surat Pindah Masyarakat | Pelayanan (Front Office) |
| 8 | Pembuatan KTP | Pelayanan (Front Office) |
| 9 | Pembuatan / Pembaruan KK | Pelayanan (Front Office) |
| 10 | Penjemputan Sampah | Kebersihan |

## 🔄 Alur Status Permohonan

`Diajukan → Menunggu Verifikasi → (Dokumen Perlu Diperbaiki) → Diproses → Disetujui / Ditolak → Selesai → Sudah Diambil`

Setiap perubahan status tercatat dalam riwayat, termasuk catatan penolakan atau permintaan revisi dokumen.

## 🎫 Antrean Digital (FIFO)

- Berlaku khusus **Seksi Pelayanan (Front Office)**: Pembuatan KTP, Surat Pindah, dan Pembuatan/Pembaruan KK.
- Kuota maksimal **30 nomor per hari** (A-001 s.d. A-030), diterbitkan berurutan sesuai waktu pendaftaran (*First In First Out*).
- Jika kuota penuh, pendaftar otomatis dijadwalkan ke hari pelayanan berikutnya.
- Admin memanggil nomor dari urutan terkecil dan menandai antrean selesai / tidak hadir.

## 📊 Klasterisasi K-Means

Volume penggunaan layanan per seksi (permohonan + antrean untuk Front Office) diklasterisasi menggunakan **K-Means Clustering**:

| Klaster | Kategori | Interpretasi |
|---|---|---|
| C1 | Beban kerja tinggi | Membutuhkan tambahan pegawai |
| C2 | Beban kerja sedang | Jumlah pegawai relatif mencukupi |
| C3 | Beban kerja rendah | Pegawai dapat membantu seksi lain |

Hasil klasterisasi ditampilkan pada dashboard pimpinan, terurut dari beban tertinggi, sebagai rekomendasi distribusi pegawai berbasis data. Kualitas klaster dievaluasi dengan **Confusion Matrix**, **Silhouette Score**, dan **Davies-Bouldin Index**.

## 🛠️ Teknologi

| Komponen | Keterangan |
|---|---|
| PHP | ^8.3 |
| Laravel | ^13 — back-end & RESTful API |
| Filament | ^5 — panel admin (Admin Seksi, Super Admin, Pimpinan) |
| MySQL | basis data |
| Pest | pengujian |

## 🚀 Instalasi

```bash
git clone https://github.com/USERNAME/si-pelayanan-camat-panakkukang.git
cd si-pelayanan-camat-panakkukang

composer install
npm install

cp .env.example .env
php artisan key:generate
# sesuaikan koneksi MySQL pada .env

php artisan migrate --seed

npm run build   # atau: npm run dev
php artisan serve
```

Panel admin dapat diakses melalui `http://localhost:8000/admin`.

## 🧪 Pengujian

```bash
php artisan test
```

Pengujian sistem dilakukan dengan **Black-Box Testing** untuk fungsionalitas, serta evaluasi K-Means menggunakan Confusion Matrix (Accuracy, Precision, Recall, F1-Score), Silhouette Score, dan Davies-Bouldin Index.

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan penelitian skripsi. Kode sumber dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).
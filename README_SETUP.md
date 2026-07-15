# Setup Laravel 13 + Filament 5

Paket ini berisi model, migration, enum, dan seeder awal untuk Sistem Informasi
Pelayanan Masyarakat Kantor Camat Panakkukang.

## 1. Persyaratan

- PHP 8.3 atau lebih baru
- Composer
- MySQL
- Node.js dan NPM

Periksa versi:

```bash
php -v
composer -V
node -v
npm -v
```

## 2. Pasang atau perbarui Laravel Installer

```bash
composer global update laravel/installer
```

Apabila Laravel Installer belum ada:

```bash
composer global require laravel/installer
```

## 3. Buat proyek Laravel

```bash
laravel new pelayanan-camat-panakkukang
cd pelayanan-camat-panakkukang
```

Pilihan yang disarankan saat installer bertanya:

- Starter kit: None
- Testing: PHPUnit atau Pest
- Database: MySQL
- Jalankan migration sekarang: No, apabila database belum dibuat

## 4. Buat database MySQL

```sql
CREATE DATABASE pelayanan_camat
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Konfigurasi `.env`:

```env
APP_NAME="Pelayanan Camat Panakkukang"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pelayanan_camat
DB_USERNAME=root
DB_PASSWORD=
```

## 5. Instal Filament 5

macOS, Linux, atau terminal biasa:

```bash
composer require filament/filament:"^5.0"
php artisan filament:install --panels
```

Windows PowerShell:

```powershell
composer require filament/filament:"~5.0"
php artisan filament:install --panels
```

## 6. Salin isi paket ini ke proyek

Salin folder berikut ke root proyek Laravel:

```text
app/Enums
app/Models
database/migrations
database/seeders
```

Catatan: paket ini mengganti `app/Models/User.php` dan
`database/seeders/DatabaseSeeder.php`.

## 7. Jalankan schema dan seeder

```bash
composer dump-autoload
php artisan optimize:clear
php artisan migrate:fresh --seed
```

## 8. Buat akun Filament

```bash
php artisan make:filament-user
```

Setelah akun dibuat, ubah role menjadi `super_admin` melalui MySQL:

```sql
UPDATE users
SET role = 'super_admin', is_active = 1
WHERE email = 'EMAIL_ADMIN_ANDA';
```

Role yang tersedia:

```text
super_admin
admin_seksi
pimpinan
masyarakat
```

## 9. Jalankan aplikasi

```bash
npm install
npm run build
composer run dev
```

Buka:

```text
http://127.0.0.1:8000/admin
```

## 10. Generate Filament Resources

Setelah migration berhasil:

```bash
php artisan make:filament-resource Section --generate
php artisan make:filament-resource Service --generate
php artisan make:filament-resource ServiceRequirement --generate
php artisan make:filament-resource ServiceApplication --generate --view
php artisan make:filament-resource ServiceQueue --generate --view
php artisan make:filament-resource KMeansRun --generate --view
```

Resource dokumen, riwayat status, dan hasil K-Means lebih baik dibuat sebagai
Relation Manager pada resource induknya. Itu dikerjakan pada tahap berikutnya.

## Struktur tabel inti

- users
- sections
- services
- service_requirements
- service_applications
- application_documents
- application_status_histories
- service_queues
- k_means_runs
- k_means_results

## Catatan desain

- `admin_seksi` dihubungkan ke satu seksi melalui `users.section_id`.
- Antrean harian menggunakan urutan unik per seksi dan tanggal.
- Kuota Seksi Pelayanan disiapkan 30 antrean per hari.
- Hasil K-Means disimpan per periode agar dapat dijadikan laporan historis.
- Kolom `form_schema` pada services disiapkan untuk formulir dinamis tiap layanan.

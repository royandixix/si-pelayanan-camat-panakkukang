<?php

namespace Database\Seeders;

use App\Models\BlackBoxTest;
use Illuminate\Database\Seeder;

class BlackBoxTestSeeder extends Seeder
{
    public function run(): void
    {
        $tests = [
            [
                'code' => 'BB-01',
                'module' => 'Login',
                'scenario' => 'Super Admin login menggunakan akun valid.',
                'test_input' => 'Email dan password valid.',
                'expected_result' => 'Sistem menerima login dan menampilkan dashboard.',
            ],
            [
                'code' => 'BB-02',
                'module' => 'Dataset Penelitian',
                'scenario' => 'Super Admin membuka Dataset Penelitian.',
                'test_input' => 'Memilih menu Dataset Penelitian.',
                'expected_result' => 'Sistem menampilkan dataset penelitian.',
            ],
            [
                'code' => 'BB-03',
                'module' => 'Dataset Penelitian',
                'scenario' => 'Super Admin melakukan filter dataset.',
                'test_input' => 'Memilih filter jenis dataset.',
                'expected_result' => 'Sistem menampilkan data sesuai filter.',
            ],
            [
                'code' => 'BB-04',
                'module' => 'Proses K-Means',
                'scenario' => 'Super Admin menjalankan proses K-Means.',
                'test_input' => 'Menekan tombol Jalankan Proses K-Means.',
                'expected_result' => 'Sistem menjalankan K-Means K=3 dan menyimpan hasil.',
            ],
            [
                'code' => 'BB-05',
                'module' => 'Proses K-Means',
                'scenario' => 'Super Admin melihat detail proses K-Means.',
                'test_input' => 'Menekan tombol Lihat.',
                'expected_result' => 'Sistem menampilkan iterasi, WCSS, Silhouette Score dan centroid.',
            ],
            [
                'code' => 'BB-06',
                'module' => 'Hasil Clustering',
                'scenario' => 'Super Admin membuka Hasil Clustering.',
                'test_input' => 'Memilih menu Hasil Clustering.',
                'expected_result' => 'Sistem menampilkan C1, C2 dan C3 beserta data hasil.',
            ],
            [
                'code' => 'BB-07',
                'module' => 'Validasi Pakar',
                'scenario' => 'Super Admin memasukkan Label Referensi.',
                'test_input' => 'Memilih Rendah, Sedang atau Tinggi.',
                'expected_result' => 'Sistem menyimpan Label Referensi tanpa mengubah hasil K-Means.',
            ],
            [
                'code' => 'BB-08',
                'module' => 'Confusion Matrix',
                'scenario' => 'Super Admin membuka Confusion Matrix.',
                'test_input' => 'Memilih menu Confusion Matrix.',
                'expected_result' => 'Sistem menampilkan matriks aktual dan prediksi.',
            ],
            [
                'code' => 'BB-09',
                'module' => 'Pengujian Akurasi',
                'scenario' => 'Super Admin membuka Pengujian Akurasi.',
                'test_input' => 'Memilih menu Pengujian Akurasi.',
                'expected_result' => 'Sistem menampilkan Accuracy, Precision, Recall dan F1-Score.',
            ],
            [
                'code' => 'BB-10',
                'module' => 'Kelola Admin',
                'scenario' => 'Super Admin membuka data admin.',
                'test_input' => 'Memilih menu Kelola Admin.',
                'expected_result' => 'Sistem menampilkan data admin.',
            ],
            [
                'code' => 'BB-11',
                'module' => 'Data Layanan',
                'scenario' => 'Super Admin membuka Data Layanan.',
                'test_input' => 'Memilih menu Data Layanan.',
                'expected_result' => 'Sistem menampilkan data layanan.',
            ],
            [
                'code' => 'BB-12',
                'module' => 'Persyaratan Layanan',
                'scenario' => 'Super Admin membuka Persyaratan Layanan.',
                'test_input' => 'Memilih menu Persyaratan Layanan.',
                'expected_result' => 'Sistem menampilkan persyaratan layanan.',
            ],
            [
                'code' => 'BB-13',
                'module' => 'Permohonan Layanan',
                'scenario' => 'Petugas membuka data Permohonan Layanan.',
                'test_input' => 'Memilih menu Permohonan Layanan.',
                'expected_result' => 'Sistem menampilkan data permohonan layanan.',
            ],
            [
                'code' => 'BB-14',
                'module' => 'Antrean Pelayanan',
                'scenario' => 'Petugas membuka Antrean Pelayanan.',
                'test_input' => 'Memilih menu Antrean Pelayanan.',
                'expected_result' => 'Sistem menampilkan data antrean pelayanan.',
            ],
            [
                'code' => 'BB-15',
                'module' => 'Hak Akses',
                'scenario' => 'Pengguna selain Super Admin mencoba mengakses Data Mining.',
                'test_input' => 'Login menggunakan role selain Super Admin.',
                'expected_result' => 'Sistem menyembunyikan menu atau menolak akses Data Mining.',
            ],
        ];

        foreach ($tests as $test) {
            BlackBoxTest::query()->updateOrCreate(
                ['code' => $test['code']],
                $test
            );
        }
    }
}

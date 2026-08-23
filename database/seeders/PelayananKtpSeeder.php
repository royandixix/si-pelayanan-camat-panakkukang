<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Service;
use App\Models\ServiceRequirement;
use Illuminate\Database\Seeder;

class PelayananKtpSeeder extends Seeder
{
    public function run(): void
    {
        $section = Section::query()
            ->where('code', 'PELAYANAN')
            ->firstOrFail();

        $section->update([
            'daily_queue_quota' => 30,
            'is_active' => true,
        ]);

        Service::query()
            ->where('code', 'PEMBUATAN_KTP')
            ->update([
                'is_active' => false,
            ]);

        $services = [
            [
                'code' => 'PEREKAMAN_EKTP',
                'name' => 'Perekaman e-KTP',
                'slug' => 'perekaman-e-ktp',
                'description' => 'Pelayanan perekaman e-KTP melalui Front Office Kecamatan Panakkukang.',
                'requirements' => [
                    [
                        'Fotokopi Kartu Keluarga',
                        'Fotokopi Kartu Keluarga dengan data yang sesuai.',
                    ],
                    [
                        'Fotokopi Ijazah',
                        'Digunakan untuk membantu pemeriksaan kesamaan data pemohon.',
                    ],
                    [
                        'Fotokopi Akta Kelahiran',
                        'Digunakan untuk membantu pemeriksaan kesamaan data pemohon.',
                    ],
                ],
                'procedure' => [
                    'Pemohon datang ke tempat pelayanan dengan membawa berkas persyaratan.',
                    'Petugas Front Office memeriksa berkas pemohon.',
                    'Petugas melakukan verifikasi data pemohon pada data SIAK.',
                    'Operator melakukan perekaman pemohon.',
                    'Pemohon mendapatkan tanda terima bukti perekaman e-KTP.',
                ],
                'time' => 'Menyesuaikan antrean dan proses pelayanan Front Office.',
                'fee' => 'Tidak dipungut biaya.',
                'note' => 'Harap periksa kesamaan data pada setiap dokumen.',
            ],
            [
                'code' => 'PEMBAHARUAN_KTP',
                'name' => 'Pembaharuan KTP',
                'slug' => 'pembaharuan-ktp',
                'description' => 'Pelayanan pembaharuan KTP melalui Front Office Kecamatan Panakkukang.',
                'requirements' => [
                    [
                        'Fotokopi Kartu Keluarga',
                        'Siapkan fotokopi Kartu Keluarga.',
                    ],
                    [
                        'KTP Fisik',
                        'Bawa KTP fisik pemohon.',
                    ],
                    [
                        'HP Android untuk IKD',
                        'Bawa HP Android untuk kebutuhan Identitas Kependudukan Digital.',
                    ],
                    [
                        'Formulir Front Office',
                        'Formulir disiapkan oleh petugas Front Office.',
                    ],
                ],
                'procedure' => [
                    'Pemohon memilih layanan Pembaharuan KTP.',
                    'Pemohon membaca dan menyiapkan seluruh persyaratan.',
                    'Pemohon mengambil nomor antrean melalui sistem.',
                    'Pemohon datang sesuai tanggal antrean.',
                    'Petugas Front Office memeriksa kelengkapan persyaratan.',
                    'Petugas melanjutkan proses pembaharuan KTP.',
                ],
                'time' => 'Menyesuaikan antrean dan proses pelayanan Front Office.',
                'fee' => 'Tidak dipungut biaya.',
                'note' => 'Pelayanan tidak dapat diwakilkan.',
            ],
            [
                'code' => 'PERUBAHAN_DATA_KTP',
                'name' => 'Perubahan Data KTP',
                'slug' => 'perubahan-data-ktp',
                'description' => 'Pelayanan perubahan data kependudukan melalui Front Office Kecamatan Panakkukang.',
                'requirements' => [
                    [
                        'Kartu Keluarga Asli',
                        'Bawa Kartu Keluarga asli.',
                    ],
                    [
                        'Surat Pernyataan dari Kelurahan',
                        'Surat pernyataan perubahan data dari kelurahan.',
                    ],
                    [
                        'Form Perubahan Data',
                        'Siapkan form perubahan data.',
                    ],
                    [
                        'KTP Asli Pemohon dan Pelapor',
                        'Bawa KTP asli pemohon dan pelapor.',
                    ],
                    [
                        'Materai Rp10.000',
                        'Siapkan materai Rp10.000.',
                    ],
                    [
                        'HP Android untuk Akun Dukcapil',
                        'Bawa HP Android untuk pendaftaran akun Dukcapil.',
                    ],
                ],
                'procedure' => [
                    'Pemohon memilih layanan Perubahan Data KTP.',
                    'Pemohon membaca dan menyiapkan seluruh persyaratan.',
                    'Pemohon mengambil nomor antrean melalui sistem.',
                    'Pemohon datang sesuai tanggal antrean.',
                    'Petugas Front Office memeriksa kelengkapan dan kesesuaian data.',
                    'Petugas memproses perubahan data sesuai ketentuan administrasi kependudukan.',
                ],
                'time' => 'Menyesuaikan antrean dan proses pelayanan Front Office.',
                'fee' => 'Pelayanan tidak dipungut biaya. Pemohon menyiapkan materai Rp10.000 sesuai persyaratan.',
                'note' => 'Pastikan seluruh data pendukung sesuai dengan perubahan yang diajukan.',
            ],
            [
                'code' => 'KTP_HILANG',
                'name' => 'KTP Hilang',
                'slug' => 'ktp-hilang',
                'description' => 'Pelayanan pengurusan KTP hilang melalui Front Office Kecamatan Panakkukang.',
                'requirements' => [
                    [
                        'Fotokopi Kartu Keluarga',
                        'Siapkan fotokopi Kartu Keluarga.',
                    ],
                    [
                        'Surat Keterangan Kehilangan dari Kepolisian',
                        'Bawa surat keterangan kehilangan dari Kepolisian.',
                    ],
                    [
                        'HP Android untuk IKD',
                        'Bawa HP Android untuk kebutuhan Identitas Kependudukan Digital.',
                    ],
                    [
                        'Formulir Front Office',
                        'Formulir disiapkan oleh petugas Front Office.',
                    ],
                ],
                'procedure' => [
                    'Pemohon memilih layanan KTP Hilang.',
                    'Pemohon membaca dan menyiapkan seluruh persyaratan.',
                    'Pemohon mengambil nomor antrean melalui sistem.',
                    'Pemohon datang sesuai tanggal antrean.',
                    'Petugas Front Office memeriksa kelengkapan persyaratan.',
                    'Petugas melanjutkan proses pengurusan KTP hilang.',
                ],
                'time' => 'Menyesuaikan antrean dan proses pelayanan Front Office.',
                'fee' => 'Tidak dipungut biaya.',
                'note' => 'Pelayanan tidak dapat diwakilkan.',
            ],
        ];

        foreach ($services as $item) {
            $service = Service::query()->updateOrCreate(
                [
                    'code' => $item['code'],
                ],
                [
                    'section_id' => $section->id,
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'description' => $item['description'],
                    'form_schema' => null,
                    'service_standard' => [
                        'persyaratan' => array_map(
                            fn (array $requirement): array => [
                                'nama' => $requirement[0],
                                'wajib' => true,
                            ],
                            $item['requirements'],
                        ),
                        'prosedur' => $item['procedure'],
                        'jangka_waktu' => $item['time'],
                        'biaya' => $item['fee'],
                        'catatan' => $item['note'],
                    ],
                    'queue_enabled' => true,
                    'processing_days' => 0,
                    'is_active' => true,
                ],
            );

            ServiceRequirement::query()
                ->where('service_id', $service->id)
                ->delete();

            foreach ($item['requirements'] as $index => $requirement) {
                ServiceRequirement::query()->create([
                    'service_id' => $service->id,
                    'name' => $requirement[0],
                    'description' => $requirement[1],
                    'allowed_extensions' => null,
                    'max_size_kb' => 0,
                    'is_required' => true,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}

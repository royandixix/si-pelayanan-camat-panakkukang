<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Service;
use App\Models\ServiceRequirement;
use Illuminate\Database\Seeder;

class StandarPelayananSeeder extends Seeder
{
    public function run(): void
    {
        $trantib = Section::query()
            ->where('code', 'TRANTIB')
            ->firstOrFail();

        $pmks = Section::query()
            ->where('code', 'PMKS')
            ->firstOrFail();

        $rekomendasi = Service::query()
            ->where('code', 'REKOMENDASI_KEGIATAN')
            ->orWhere('slug', 'rekomendasi-kegiatan')
            ->first();

        $rekomendasi ??= new Service();

        $rekomendasi->fill([
            'section_id' => $trantib->id,
            'code' => 'REKOMENDASI_KEGIATAN',
            'name' => 'Rekomendasi Kegiatan',
            'slug' => 'rekomendasi-kegiatan',
            'description' => 'Pelayanan penerbitan surat rekomendasi kegiatan yang diajukan oleh masyarakat, organisasi, lembaga, atau penanggung jawab kegiatan.',
            'queue_enabled' => false,
            'processing_days' => 2,
            'is_active' => true,
            'service_standard' => [
                'persyaratan' => [
                    [
                        'nama' => 'Surat permohonan rekomendasi kegiatan',
                        'wajib' => true,
                    ],
                    [
                        'nama' => 'Fotokopi identitas pemohon atau penanggung jawab kegiatan',
                        'wajib' => true,
                    ],
                    [
                        'nama' => 'Proposal kegiatan atau uraian kegiatan',
                        'wajib' => false,
                    ],
                    [
                        'nama' => 'Surat pengantar dari kelurahan apabila diperlukan',
                        'wajib' => false,
                    ],
                    [
                        'nama' => 'Dokumen pendukung lain sesuai jenis kegiatan',
                        'wajib' => false,
                    ],
                ],
                'prosedur' => [
                    'Pemohon mengajukan surat permohonan rekomendasi kegiatan kepada Kecamatan Panakkukang.',
                    'Petugas menerima dan memeriksa kelengkapan berkas.',
                    'Berkas diregistrasi dalam buku atau aplikasi persuratan.',
                    'Berkas diverifikasi oleh seksi atau pejabat terkait.',
                    'Koordinasi atau peninjauan lokasi dilakukan apabila diperlukan.',
                    'Surat rekomendasi diproses dan diajukan kepada Camat atau pejabat berwenang.',
                    'Surat ditandatangani secara manual atau elektronik melalui aplikasi SRIKANDI.',
                    'Surat rekomendasi diserahkan kepada pemohon.',
                    'Arsip dokumen disimpan oleh pengelola pelayanan.',
                ],
                'jangka_waktu' => 'Maksimal 2 hari kerja sejak berkas dinyatakan lengkap.',
                'biaya' => 'Gratis atau tidak dipungut biaya.',
                'produk' => [
                    'Surat rekomendasi kegiatan.',
                    'Registrasi pelayanan rekomendasi kegiatan.',
                ],
                'pengaduan' => [
                    'Email: panakkukangkec@makassarkota.go.id',
                    'Instagram: @kec.panakkukang',
                    'Aplikasi Lontara+',
                ],
            ],
        ]);

        $rekomendasi->save();

        $this->simpanPersyaratan(
            $rekomendasi,
            [
                [
                    'name' => 'Surat Permohonan Rekomendasi Kegiatan',
                    'description' => 'Surat permohonan resmi yang ditujukan kepada Kecamatan Panakkukang.',
                    'is_required' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Identitas Pemohon atau Penanggung Jawab',
                    'description' => 'Fotokopi KTP atau dokumen identitas pemohon maupun penanggung jawab kegiatan.',
                    'is_required' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Proposal atau Uraian Kegiatan',
                    'description' => 'Proposal atau uraian kegiatan apabila tersedia.',
                    'is_required' => false,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Surat Pengantar Kelurahan',
                    'description' => 'Surat pengantar kelurahan apabila dipersyaratkan.',
                    'is_required' => false,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Dokumen Pendukung Lainnya',
                    'description' => 'Dokumen tambahan sesuai jenis dan kebutuhan kegiatan.',
                    'is_required' => false,
                    'sort_order' => 5,
                ],
            ],
        );

        $ahliWaris = Service::query()
            ->where('code', 'AHLI_WARIS')
            ->orWhere('slug', 'keterangan-ahli-waris')
            ->first();

        $ahliWaris ??= new Service();

        $ahliWaris->fill([
            'section_id' => $pmks->id,
            'code' => 'AHLI_WARIS',
            'name' => 'Keterangan Ahli Waris',
            'slug' => 'keterangan-ahli-waris',
            'description' => 'Pelayanan pemeriksaan dan penandatanganan Surat Pernyataan Ahli Waris oleh Camat Panakkukang.',
            'queue_enabled' => false,
            'processing_days' => 1,
            'is_active' => true,
            'service_standard' => [
                'persyaratan' => [
                    [
                        'nama' => 'Surat Pernyataan Ahli Waris yang diterbitkan kelurahan',
                        'wajib' => true,
                    ],
                    [
                        'nama' => 'Surat telah ditandatangani oleh RT, RW, dan Lurah',
                        'wajib' => true,
                    ],
                    [
                        'nama' => 'Akta Kematian Pewaris',
                        'wajib' => true,
                    ],
                    [
                        'nama' => 'Fotokopi Buku Nikah Pewaris',
                        'wajib' => true,
                    ],
                    [
                        'nama' => 'Fotokopi KTP dan KK semua ahli waris',
                        'wajib' => true,
                    ],
                    [
                        'nama' => 'Foto masing-masing ahli waris saat bertanda tangan',
                        'wajib' => true,
                    ],
                ],
                'prosedur' => [
                    'Pemohon menyerahkan surat beserta dokumen pendukung kepada petugas di ruangan Kesra.',
                    'Petugas menerima dan memeriksa kelengkapan surat.',
                    'Surat dicatat dalam buku agenda Surat Pernyataan Ahli Waris.',
                    'Pemohon mencatat nama dan nomor telepon serta menandatangani buku agenda.',
                    'Surat diproses untuk penandatanganan oleh Camat.',
                ],
                'jangka_waktu' => 'Penerimaan dan pencatatan maksimal 10 menit. Penandatanganan maksimal 1 hari kerja.',
                'biaya' => 'Gratis atau tidak dipungut biaya.',
                'produk' => [
                    'Penandatanganan Surat Pernyataan Ahli Waris oleh Camat.',
                ],
                'pengaduan' => [
                    'Email: panakkukangkec@makassarkota.go.id',
                    'Instagram: @kec.panakkukang',
                    'Aplikasi Lontara+',
                ],
            ],
        ]);

        $ahliWaris->save();

        $this->simpanPersyaratan(
            $ahliWaris,
            [
                [
                    'name' => 'Surat Pernyataan Ahli Waris',
                    'description' => 'Surat Pernyataan Ahli Waris yang diterbitkan oleh kelurahan dan telah ditandatangani RT, RW, serta Lurah.',
                    'is_required' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Akta Kematian Pewaris',
                    'description' => 'Salinan Akta Kematian dari pewaris.',
                    'is_required' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Buku Nikah Pewaris',
                    'description' => 'Fotokopi Buku Nikah Pewaris.',
                    'is_required' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'KTP dan KK Semua Ahli Waris',
                    'description' => 'Fotokopi KTP dan Kartu Keluarga seluruh ahli waris.',
                    'is_required' => true,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Foto Ahli Waris Saat Bertanda Tangan',
                    'description' => 'Foto masing-masing ahli waris ketika menandatangani surat.',
                    'is_required' => true,
                    'sort_order' => 5,
                ],
            ],
        );
    }

    private function simpanPersyaratan(
        Service $service,
        array $persyaratan,
    ): void {
        foreach ($persyaratan as $item) {
            ServiceRequirement::query()->updateOrCreate(
                [
                    'service_id' => $service->id,
                    'name' => $item['name'],
                ],
                [
                    'description' => $item['description'],
                    'allowed_extensions' => [
                        'pdf',
                        'jpg',
                        'jpeg',
                        'png',
                    ],
                    'max_size_kb' => 3072,
                    'is_required' => $item['is_required'],
                    'sort_order' => $item['sort_order'],
                ],
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['section' => 'PMKS', 'code' => 'AHLI_WARIS', 'name' => 'Keterangan Ahli Waris', 'queue' => false],
            ['section' => 'PMKS', 'code' => 'IZIN_MENELITI', 'name' => 'Izin Meneliti', 'queue' => false],
            ['section' => 'PEM', 'code' => 'KONSULTASI_PERTANAHAN', 'name' => 'Konsultasi Pertanahan', 'queue' => false],
            ['section' => 'PEM', 'code' => 'KONSULTASI_PPAT', 'name' => 'Konsultasi PPAT', 'queue' => false],
            ['section' => 'TRANTIB', 'code' => 'PENGADUAN', 'name' => 'Pengaduan', 'queue' => false],
            ['section' => 'TRANTIB', 'code' => 'REKOMENDASI_KEGIATAN', 'name' => 'Rekomendasi Kegiatan', 'queue' => false],
            ['section' => 'PELAYANAN', 'code' => 'SURAT_PINDAH', 'name' => 'Surat Pindah Masyarakat', 'queue' => true],
            ['section' => 'PELAYANAN', 'code' => 'PEMBUATAN_KTP', 'name' => 'Pembuatan KTP', 'queue' => true],
            ['section' => 'PELAYANAN', 'code' => 'KARTU_KELUARGA', 'name' => 'Pembuatan/Pembaruan KK', 'queue' => true],
            ['section' => 'KEBERSIHAN', 'code' => 'PENJEMPUTAN_SAMPAH', 'name' => 'Penjemputan Sampah', 'queue' => false],
        ];

        foreach ($services as $item) {
            $section = Section::query()
                ->where('code', $item['section'])
                ->firstOrFail();

            Service::query()->updateOrCreate(
                ['code' => $item['code']],
                [
                    'section_id' => $section->id,
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']),
                    'description' => null,
                    'form_schema' => null,
                    'queue_enabled' => $item['queue'],
                    'processing_days' => null,
                    'is_active' => true,
                ],
            );
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\ResearchDatasetRecord;
use App\Models\Section;
use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ImportResearchDataset extends Command
{
    protected $signature = 'dataset:import';

    protected $description = 'Import dataset penelitian riil Kecamatan Panakkukang';

    public function handle(): int
    {
        $this->newLine();
        $this->info('IMPORT DATASET RIIL KECAMATAN PANAKKUKANG');
        $this->line(str_repeat('=', 55));

        $this->importPewarisan();
        $this->importIzinMeneliti();
        $this->importRekomendasiKegiatan();

        $this->line(str_repeat('-', 55));

        $total = ResearchDatasetRecord::query()->count();

        $this->info("Total data dalam database: {$total}");
        $this->info('Import dataset riil selesai.');

        return self::SUCCESS;
    }

    private function importPewarisan(): void
    {
        $section = Section::query()
            ->where('code', 'PMKS')
            ->firstOrFail();

        $service = Service::query()
            ->where('code', 'AHLI_WARIS')
            ->firstOrFail();

        $this->importCsv(
            storage_path('app/import/csv/pewarisan.csv'),
            $section->id,
            $service->id,
            'Pewarisan',
            'pewarisan.pdf',
            'nama',
            null,
        );
    }

    private function importIzinMeneliti(): void
    {
        $section = Section::query()
            ->where('code', 'PMKS')
            ->firstOrFail();

        $service = Service::query()
            ->where('code', 'IZIN_MENELITI')
            ->firstOrFail();

        $this->importCsv(
            storage_path('app/import/csv/izin_meneliti.csv'),
            $section->id,
            $service->id,
            'Izin Meneliti',
            'izin_meneliti.pdf',
            'nama',
            null,
        );
    }

    private function importRekomendasiKegiatan(): void
    {
        $section = Section::query()
            ->where('code', 'TRANTIB')
            ->firstOrFail();

        $service = Service::query()
            ->where('code', 'REKOMENDASI_KEGIATAN')
            ->firstOrFail();

        $this->importCsv(
            storage_path('app/import/csv/rekomendasi_kegiatan.csv'),
            $section->id,
            $service->id,
            'Rekomendasi Kegiatan',
            'rekomendasi_kegiatan.pdf',
            null,
            'keterangan',
        );
    }

    private function importCsv(
        string $path,
        int $sectionId,
        int $serviceId,
        string $datasetName,
        string $sourceFile,
        ?string $nameColumn,
        ?string $descriptionColumn,
    ): void {
        if (! file_exists($path)) {
            throw new RuntimeException("File tidak ditemukan: {$path}");
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException("File tidak dapat dibuka: {$path}");
        }

        $headers = fgetcsv(
            $handle,
            null,
            ',',
            '"',
            '',
        );

        if ($headers === false) {
            fclose($handle);

            throw new RuntimeException("Header CSV tidak ditemukan: {$path}");
        }

        $headers = array_map(
            fn ($header): string => strtolower(
                trim(
                    ltrim((string) $header, "\xEF\xBB\xBF")
                )
            ),
            $headers,
        );

        $processed = 0;

        DB::transaction(function () use (
            $handle,
            $headers,
            $sectionId,
            $serviceId,
            $datasetName,
            $sourceFile,
            $nameColumn,
            $descriptionColumn,
            &$processed,
        ): void {
            while (($row = fgetcsv(
                $handle,
                null,
                ',',
                '"',
                '',
            )) !== false) {
                if (count($row) !== count($headers)) {
                    continue;
                }

                $data = array_combine(
                    $headers,
                    $row,
                );

                if ($data === false) {
                    continue;
                }

                $sourceRowNo = isset($data['no'])
                    ? (int) $data['no']
                    : null;

                $recordDate = trim(
                    (string) ($data['tanggal'] ?? '')
                );

                $rawDate = trim(
                    (string) (
                        $data['tanggal_asli']
                        ?? $recordDate
                    )
                );

                $subjectName = $nameColumn
                    ? trim((string) ($data[$nameColumn] ?? ''))
                    : null;

                $description = $descriptionColumn
                    ? trim((string) ($data[$descriptionColumn] ?? ''))
                    : null;

                $validationStatus = str_starts_with(
                    $recordDate,
                    '2025-',
                )
                    ? 'valid'
                    : 'needs_review';

                ResearchDatasetRecord::query()->updateOrCreate(
                    [
                        'source_file' => $sourceFile,
                        'source_row_no' => $sourceRowNo,
                    ],
                    [
                        'section_id' => $sectionId,
                        'service_id' => $serviceId,
                        'dataset_name' => $datasetName,
                        'record_date' => $recordDate ?: null,
                        'raw_date' => $rawDate ?: null,
                        'subject_name' => $subjectName ?: null,
                        'description' => $description ?: null,
                        'validation_status' => $validationStatus,
                    ],
                );

                $processed++;
            }
        });

        fclose($handle);

        $this->info(
            "{$datasetName}: {$processed} data berhasil diimport"
        );
    }
}

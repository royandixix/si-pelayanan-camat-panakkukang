<?php

namespace App\Filament\Widgets;

use App\Models\ResearchDatasetRecord;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ResearchDatasetStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = ResearchDatasetRecord::query()->count();

        $pewarisan = ResearchDatasetRecord::query()
            ->where('dataset_name', 'Pewarisan')
            ->count();

        $izinMeneliti = ResearchDatasetRecord::query()
            ->where('dataset_name', 'Izin Meneliti')
            ->count();

        $rekomendasi = ResearchDatasetRecord::query()
            ->where('dataset_name', 'Rekomendasi Kegiatan')
            ->count();

        $needsReview = ResearchDatasetRecord::query()
            ->where('validation_status', 'needs_review')
            ->count();

        return [
            Stat::make('Total Dataset', number_format($total))
                ->description('Seluruh data penelitian'),

            Stat::make('Pewarisan', number_format($pewarisan))
                ->description('Keterangan Ahli Waris'),

            Stat::make('Izin Meneliti', number_format($izinMeneliti))
                ->description('Dataset izin penelitian'),

            Stat::make('Rekomendasi Kegiatan', number_format($rekomendasi))
                ->description('Seksi Trantib'),

            Stat::make('Perlu Verifikasi', number_format($needsReview))
                ->description('Tanggal sumber perlu diperiksa'),
        ];
    }
}

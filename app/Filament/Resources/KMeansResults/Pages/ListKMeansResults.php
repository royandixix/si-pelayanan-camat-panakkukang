<?php

namespace App\Filament\Resources\KMeansResults\Pages;

use App\Filament\Resources\KMeansResults\KMeansResultResource;
use App\Filament\Widgets\ClusterDistributionChart;
use App\Filament\Widgets\KMeansSummaryStats;
use Filament\Resources\Pages\ListRecords;

class ListKMeansResults extends ListRecords
{
    protected static string $resource =
        KMeansResultResource::class;

    protected static ?string $title =
        'Hasil Clustering';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            KMeansSummaryStats::class,
            ClusterDistributionChart::class,
        ];
    }
}

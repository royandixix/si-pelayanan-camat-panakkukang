<?php

namespace App\Filament\Resources\ResearchDatasetRecords\Pages;

use App\Filament\Resources\ResearchDatasetRecords\ResearchDatasetRecordResource;
use App\Filament\Widgets\ResearchDatasetStats;
use Filament\Resources\Pages\ListRecords;

class ListResearchDatasetRecords extends ListRecords
{
    protected static string $resource = ResearchDatasetRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ResearchDatasetStats::class,
        ];
    }
}

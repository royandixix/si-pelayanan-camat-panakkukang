<?php

namespace App\Filament\Resources\ResearchDatasetRecords\Pages;

use App\Filament\Resources\ResearchDatasetRecords\ResearchDatasetRecordResource;
use Filament\Resources\Pages\ViewRecord;

class ViewResearchDatasetRecord extends ViewRecord
{
    protected static string $resource = ResearchDatasetRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

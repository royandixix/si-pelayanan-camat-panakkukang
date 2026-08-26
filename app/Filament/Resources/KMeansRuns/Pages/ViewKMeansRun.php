<?php

namespace App\Filament\Resources\KMeansRuns\Pages;

use App\Filament\Resources\KMeansRuns\KMeansRunResource;
use Filament\Resources\Pages\ViewRecord;

class ViewKMeansRun extends ViewRecord
{
    protected static string $resource = KMeansRunResource::class;

    protected static ?string $title = 'Detail Proses K-Means';

    protected function getHeaderActions(): array
    {
        return [];
    }
}

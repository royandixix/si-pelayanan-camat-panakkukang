<?php

namespace App\Filament\Pimpinan\Resources\KMeansResults\Pages;

use App\Filament\Pimpinan\Resources\KMeansResults\KMeansResultResource;
use Filament\Resources\Pages\ViewRecord;

class ViewKMeansResult extends ViewRecord
{
    protected static string $resource = KMeansResultResource::class;

    protected static ?string $title = 'Detail Hasil Analisis K-Means';

    protected function getHeaderActions(): array
    {
        return [];
    }
}

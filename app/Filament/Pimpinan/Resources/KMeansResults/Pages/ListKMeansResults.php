<?php

namespace App\Filament\Pimpinan\Resources\KMeansResults\Pages;

use App\Filament\Pimpinan\Resources\KMeansResults\KMeansResultResource;
use Filament\Resources\Pages\ListRecords;

class ListKMeansResults extends ListRecords
{
    protected static string $resource=KMeansResultResource::class;

    protected static ?string $title='Analisis K-Means';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
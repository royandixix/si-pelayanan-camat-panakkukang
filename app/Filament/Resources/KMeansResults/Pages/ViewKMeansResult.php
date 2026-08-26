<?php

namespace App\Filament\Resources\KMeansResults\Pages;

use App\Filament\Resources\KMeansResults\KMeansResultResource;
use Filament\Resources\Pages\ViewRecord;

class ViewKMeansResult extends ViewRecord
{
    protected static string $resource = KMeansResultResource::class;

    protected static ?string $title = 'Detail Hasil Clustering';

    protected function getHeaderActions(): array
    {
        return [];
    }
}

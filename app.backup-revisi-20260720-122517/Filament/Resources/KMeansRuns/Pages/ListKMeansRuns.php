<?php

namespace App\Filament\Resources\KMeansRuns\Pages;

use App\Filament\Resources\KMeansRuns\KMeansRunResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKMeansRuns extends ListRecords
{
    protected static string $resource = KMeansRunResource::class;

    protected static ?string $title = 'Daftar Proses K-Means';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Proses K-Means'),
        ];
    }
}
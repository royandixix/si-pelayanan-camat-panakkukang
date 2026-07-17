<?php

namespace App\Filament\Pimpinan\Resources\ServiceApplications\Pages;

use App\Filament\Pimpinan\Resources\ServiceApplications\ServiceApplicationResource;
use Filament\Resources\Pages\ListRecords;

class ListServiceApplications extends ListRecords
{
    protected static string $resource=ServiceApplicationResource::class;

    protected static ?string $title='Monitoring Permohonan';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
<?php

namespace App\Filament\Resources\ServiceApplications\Pages;

use App\Filament\Resources\ServiceApplications\ServiceApplicationResource;
use Filament\Resources\Pages\ListRecords;

class ListServiceApplications extends ListRecords
{
    protected static string $resource = ServiceApplicationResource::class;

    protected static ?string $title = 'Daftar Permohonan Layanan';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
<?php

namespace App\Filament\Pimpinan\Resources\ServiceReports\Pages;

use App\Filament\Pimpinan\Resources\ServiceReports\ServiceReportResource;
use Filament\Resources\Pages\ListRecords;

class ListServiceReports extends ListRecords
{
    protected static string $resource=ServiceReportResource::class;

    protected static ?string $title='Laporan Pelayanan';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
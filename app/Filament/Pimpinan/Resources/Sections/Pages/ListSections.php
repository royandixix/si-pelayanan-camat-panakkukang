<?php

namespace App\Filament\Pimpinan\Resources\Sections\Pages;

use App\Filament\Pimpinan\Resources\Sections\SectionResource;
use Filament\Resources\Pages\ListRecords;

class ListSections extends ListRecords
{
    protected static string $resource=SectionResource::class;

    protected static ?string $title='Data Seksi';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
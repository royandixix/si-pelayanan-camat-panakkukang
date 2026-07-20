<?php

namespace App\Filament\Pimpinan\Resources\Sections\Pages;

use App\Filament\Pimpinan\Resources\Sections\SectionResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSection extends ViewRecord
{
    protected static string $resource=SectionResource::class;

    protected static ?string $title='Detail Seksi';

    protected function getHeaderActions(): array
    {
        return [];
    }
}

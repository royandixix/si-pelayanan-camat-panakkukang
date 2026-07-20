<?php

namespace App\Filament\Pimpinan\Resources\ServiceApplications\Pages;

use App\Filament\Pimpinan\Resources\ServiceApplications\ServiceApplicationResource;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceApplication extends ViewRecord
{
    protected static string $resource=ServiceApplicationResource::class;

    protected static ?string $title='Detail Permohonan';

    protected function getHeaderActions(): array
    {
        return [];
    }
}

<?php

namespace App\Filament\Resources\ServiceQueues\Pages;

use App\Filament\Resources\ServiceQueues\ServiceQueueResource;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceQueue extends ViewRecord
{
    protected static string $resource = ServiceQueueResource::class;

    protected static ?string $title = 'Detail Antrean Pelayanan';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
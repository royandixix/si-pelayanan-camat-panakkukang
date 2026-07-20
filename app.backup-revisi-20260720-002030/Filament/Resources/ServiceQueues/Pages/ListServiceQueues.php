<?php

namespace App\Filament\Resources\ServiceQueues\Pages;

use App\Filament\Resources\ServiceQueues\ServiceQueueResource;
use Filament\Resources\Pages\ListRecords;

class ListServiceQueues extends ListRecords
{
    protected static string $resource = ServiceQueueResource::class;

    protected static ?string $title = 'Daftar Antrean Pelayanan';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
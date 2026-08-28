<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected static ?string $title = 'Data Layanan Berdasarkan Seksi';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Layanan')
                ->visible(
                    fn (): bool =>
                        ServiceResource::canCreate(),
                ),
        ];
    }
}
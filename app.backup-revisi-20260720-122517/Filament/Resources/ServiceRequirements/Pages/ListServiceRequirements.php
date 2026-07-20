<?php

namespace App\Filament\Resources\ServiceRequirements\Pages;

use App\Filament\Resources\ServiceRequirements\ServiceRequirementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceRequirements extends ListRecords
{
    protected static string $resource = ServiceRequirementResource::class;

    protected static ?string $title = 'Daftar Persyaratan Layanan';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Persyaratan'),
        ];
    }
}
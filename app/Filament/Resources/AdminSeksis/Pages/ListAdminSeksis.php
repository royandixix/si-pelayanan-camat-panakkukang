<?php

namespace App\Filament\Resources\AdminSeksis\Pages;

use App\Filament\Resources\AdminSeksis\AdminSeksiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdminSeksis extends ListRecords
{
    protected static string $resource = AdminSeksiResource::class;

    public function getTitle(): string
    {
        return 'Kelola Admin Seksi';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Admin'),
        ];
    }
}

<?php

namespace App\Filament\Resources\ServiceRequirements\Pages;

use App\Filament\Resources\ServiceRequirements\ServiceRequirementResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceRequirement extends ViewRecord
{
    protected static string $resource = ServiceRequirementResource::class;

    protected static ?string $title = 'Detail Persyaratan Layanan';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Ubah'),
        ];
    }
}
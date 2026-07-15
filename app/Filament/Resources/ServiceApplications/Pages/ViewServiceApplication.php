<?php

namespace App\Filament\Resources\ServiceApplications\Pages;

use App\Filament\Resources\ServiceApplications\ServiceApplicationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceApplication extends ViewRecord
{
    protected static string $resource = ServiceApplicationResource::class;

    protected static ?string $title = 'Detail Permohonan Layanan';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Ubah'),
        ];
    }
}
<?php

namespace App\Filament\Resources\Sections\Pages;

use App\Filament\Resources\Sections\SectionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSection extends ViewRecord
{
    protected static string $resource = SectionResource::class;

    protected static ?string $title = 'Detail Seksi';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Ubah'),
        ];
    }
}
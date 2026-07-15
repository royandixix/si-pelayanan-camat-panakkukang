<?php

namespace App\Filament\Resources\ServiceApplications\Pages;

use App\Filament\Resources\ServiceApplications\ServiceApplicationResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceApplication extends EditRecord
{
    protected static string $resource = ServiceApplicationResource::class;

    protected static ?string $title = 'Ubah Permohonan Layanan';

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Lihat'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Simpan Perubahan'),
            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}
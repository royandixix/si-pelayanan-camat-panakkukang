<?php

namespace App\Filament\Resources\ServiceRequirements\Pages;

use App\Filament\Resources\ServiceRequirements\ServiceRequirementResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceRequirement extends EditRecord
{
    protected static string $resource = ServiceRequirementResource::class;

    protected static ?string $title = 'Ubah Persyaratan Layanan';

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Lihat'),
            DeleteAction::make()
                ->label('Hapus')
                ->modalHeading('Hapus persyaratan layanan')
                ->modalDescription('Apakah Anda yakin ingin menghapus persyaratan layanan ini?')
                ->modalSubmitActionLabel('Hapus')
                ->modalCancelActionLabel('Batal'),
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
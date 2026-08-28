<?php

namespace App\Filament\Actions;

use App\Enums\ApplicationStatus;
use App\Models\ServiceApplication;
use App\Models\User;
use App\Services\ApplicationResultService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class UploadApplicationResultAction
{
    public static function make(): Action
    {
        return Action::make('unggahHasilPelayanan')
            ->label('Terbitkan Hasil')
            ->icon('heroicon-o-document-arrow-up')
            ->color('success')
            ->modalHeading('Terbitkan Dokumen Hasil Pelayanan')
            ->modalDescription(
                'Unggah dokumen hasil resmi dalam format PDF. Permohonan akan otomatis berstatus selesai.',
            )
            ->modalSubmitActionLabel('Terbitkan Hasil')
            ->schema([
                FileUpload::make('path')
                    ->label('Dokumen hasil')
                    ->disk('local')
                    ->directory(
                        fn (
                            ServiceApplication $record,
                        ): string =>
                            'hasil-pelayanan/' . $record->id,
                    )
                    ->acceptedFileTypes([
                        'application/pdf',
                    ])
                    ->maxSize(10240)
                    ->storeFileNamesIn('original_name')
                    ->visibility('private')
                    ->required(),

                Textarea::make('notes')
                    ->label('Keterangan')
                    ->rows(3)
                    ->maxLength(1000),
            ])
            ->fillForm(function (
                ServiceApplication $record,
            ): array {
                $record->loadMissing('result');

                return [
                    'notes' => $record->result?->notes,
                ];
            })
            ->action(function (
                array $data,
                ServiceApplication $record,
            ): void {
                $pengguna = auth()->user();

                abort_unless(
                    $pengguna instanceof User
                    && $pengguna->is_active
                    && $pengguna->isAdminSeksi()
                    && $pengguna->section_id !== null
                    && $record->service?->section_id === $pengguna->section_id,
                    403,
                );

                app(ApplicationResultService::class)->publish(
                    application: $record,
                    actor: $pengguna,
                    path: (string) $data['path'],
                    originalName: (string) (
                        $data['original_name']
                        ?? basename((string) $data['path'])
                    ),
                    notes: $data['notes'] ?? null,
                );

                Notification::make()
                    ->success()
                    ->title('Dokumen hasil diterbitkan')
                    ->body(
                        'Permohonan telah selesai dan masyarakat sudah menerima notifikasi.',
                    )
                    ->send();
            })
            ->visible(function (
                ServiceApplication $record,
            ): bool {
                $pengguna = auth()->user();

                if (
                    ! $pengguna instanceof User
                    || ! $pengguna->isAdminSeksi()
                    || $pengguna->section_id === null
                    || $record->service?->section_id !== $pengguna->section_id
                ) {
                    return false;
                }

                $status = $record->status instanceof BackedEnum
                    ? $record->status->value
                    : (string) $record->status;

                return in_array(
                    $status,
                    [
                        ApplicationStatus::PROCESSING->value,
                        ApplicationStatus::COMPLETED->value,
                    ],
                    true,
                );
            });
    }
}

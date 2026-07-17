<?php

namespace App\Filament\Resources\ServiceApplications\Pages;

use App\Enums\ApplicationStatus;
use App\Filament\Resources\ServiceApplications\ServiceApplicationResource;
use App\Services\ApplicationWorkflowService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceApplication extends ViewRecord
{
    protected static string $resource = ServiceApplicationResource::class;

    protected static ?string $title = 'Detail Permohonan Layanan';

    protected function getHeaderActions(): array
    {
        return [
            $this->statusAction(
                'startVerification',
                'Mulai Verifikasi',
                ApplicationStatus::VERIFICATION,
                'heroicon-o-document-magnifying-glass',
                'warning',
                [ApplicationStatus::SUBMITTED],
            ),

            $this->statusAction(
                'requestRevision',
                'Minta Perbaikan',
                ApplicationStatus::REVISION,
                'heroicon-o-arrow-path',
                'danger',
                [ApplicationStatus::VERIFICATION],
                true,
            ),

            $this->statusAction(
                'approve',
                'Setujui',
                ApplicationStatus::APPROVED,
                'heroicon-o-check-circle',
                'success',
                [ApplicationStatus::VERIFICATION],
            ),

            $this->statusAction(
                'reject',
                'Tolak',
                ApplicationStatus::REJECTED,
                'heroicon-o-x-circle',
                'danger',
                [
                    ApplicationStatus::SUBMITTED,
                    ApplicationStatus::VERIFICATION,
                    ApplicationStatus::REVISION,
                    ApplicationStatus::PROCESSING,
                ],
                true,
            ),

            $this->statusAction(
                'startProcessing',
                'Mulai Diproses',
                ApplicationStatus::PROCESSING,
                'heroicon-o-cog-6-tooth',
                'info',
                [ApplicationStatus::APPROVED],
            ),

            $this->statusAction(
                'complete',
                'Selesaikan',
                ApplicationStatus::COMPLETED,
                'heroicon-o-check-badge',
                'success',
                [ApplicationStatus::PROCESSING],
            ),

            $this->statusAction(
                'collect',
                'Sudah Diambil',
                ApplicationStatus::COLLECTED,
                'heroicon-o-archive-box-arrow-down',
                'gray',
                [ApplicationStatus::COMPLETED],
            ),
        ];
    }

    private function statusAction(
        string $name,
        string $label,
        ApplicationStatus $targetStatus,
        string $icon,
        string $color,
        array $visibleStatuses,
        bool $notesRequired = false,
    ): Action {
        return Action::make($name)
            ->label($label)
            ->icon($icon)
            ->color($color)
            ->visible(
                fn (): bool => in_array(
                    $this->record->status,
                    $visibleStatuses,
                    true,
                ),
            )
            ->schema([
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(4)
                    ->required($notesRequired),
            ])
            ->requiresConfirmation()
            ->modalHeading($label)
            ->modalSubmitActionLabel($label)
            ->modalCancelActionLabel('Batal')
            ->action(function (array $data) use ($targetStatus, $label): void {
                app(ApplicationWorkflowService::class)->changeStatus(
                    $this->record,
                    $targetStatus,
                    auth()->user(),
                    $data['notes'] ?? null,
                );

                $this->record->refresh();

                Notification::make()
                    ->title($label . ' berhasil')
                    ->success()
                    ->send();
            });
    }
}
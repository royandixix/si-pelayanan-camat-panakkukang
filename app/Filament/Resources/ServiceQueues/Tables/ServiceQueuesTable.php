<?php

namespace App\Filament\Resources\ServiceQueues\Tables;

use App\Enums\QueueStatus;
use App\Models\ServiceQueue;
use App\Services\QueueFifoService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Throwable;

class ServiceQueuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor')
                    ->label('No.')
                    ->rowIndex(),

                TextColumn::make('queue_number')
                    ->label('Nomor Antrean')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('sequence')
                    ->label('Urutan FIFO')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Nama Masyarakat')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('service.name')
                    ->label('Jenis Layanan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('section.name')
                    ->label('Seksi')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('queue_date')
                    ->label('Tanggal Antrean')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state): string =>
                            $state instanceof QueueStatus
                                ? $state->label()
                                : (
                                    QueueStatus::tryFrom((string) $state)
                                        ?->label()
                                    ?? (string) $state
                                )
                    )
                    ->color(
                        fn ($state): string => match (
                            $state instanceof QueueStatus
                                ? $state
                                : QueueStatus::tryFrom((string) $state)
                        ) {
                            QueueStatus::WAITING => 'warning',
                            QueueStatus::CALLED => 'info',
                            QueueStatus::SERVING => 'primary',
                            QueueStatus::COMPLETED => 'success',
                            QueueStatus::SKIPPED => 'gray',
                            QueueStatus::CANCELLED => 'danger',
                            default => 'gray',
                        }
                    ),

                TextColumn::make('registered_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),

                TextColumn::make('called_at')
                    ->label('Dipanggil')
                    ->dateTime('d M Y H:i:s')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('service_started_at')
                    ->label('Mulai Dilayani')
                    ->dateTime('d M Y H:i:s')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('served_at')
                    ->label('Selesai')
                    ->dateTime('d M Y H:i:s')
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Antrean')
                    ->options(
                        collect(QueueStatus::cases())
                            ->mapWithKeys(
                                fn (QueueStatus $status): array => [
                                    $status->value => $status->label(),
                                ]
                            )
                            ->all()
                    ),

                SelectFilter::make('service_id')
                    ->label('Jenis Layanan')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('section_id')
                    ->label('Seksi')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),

                Action::make('callQueue')
                    ->label('Panggil')
                    ->icon('heroicon-o-megaphone')
                    ->color('info')
                    ->visible(
                        fn (ServiceQueue $record): bool =>
                            $record->status === QueueStatus::WAITING
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Panggil antrean')
                    ->modalSubmitActionLabel('Panggil')
                    ->action(
                        fn (ServiceQueue $record) =>
                            self::runAction(
                                fn () => app(QueueFifoService::class)
                                    ->call($record),
                                'Antrean '.$record->queue_number.' berhasil dipanggil.'
                            )
                    ),

                Action::make('startService')
                    ->label('Mulai Dilayani')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->visible(
                        fn (ServiceQueue $record): bool =>
                            $record->status === QueueStatus::CALLED
                    )
                    ->action(
                        fn (ServiceQueue $record) =>
                            self::runAction(
                                fn () => app(QueueFifoService::class)
                                    ->start($record),
                                'Pelayanan '.$record->queue_number.' dimulai.'
                            )
                    ),

                Action::make('completeService')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(
                        fn (ServiceQueue $record): bool =>
                            $record->status === QueueStatus::SERVING
                    )
                    ->requiresConfirmation()
                    ->action(
                        fn (ServiceQueue $record) =>
                            self::runAction(
                                fn () => app(QueueFifoService::class)
                                    ->complete($record),
                                'Antrean '.$record->queue_number.' selesai dilayani.'
                            )
                    ),

                Action::make('skipQueue')
                    ->label('Lewati')
                    ->icon('heroicon-o-forward')
                    ->color('gray')
                    ->visible(
                        fn (ServiceQueue $record): bool =>
                            $record->status === QueueStatus::CALLED
                    )
                    ->requiresConfirmation()
                    ->action(
                        fn (ServiceQueue $record) =>
                            self::runAction(
                                fn () => app(QueueFifoService::class)
                                    ->skip($record),
                                'Antrean '.$record->queue_number.' ditandai terlewati.'
                            )
                    ),

                Action::make('cancelQueue')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(
                        fn (ServiceQueue $record): bool =>
                            in_array(
                                $record->status,
                                [
                                    QueueStatus::WAITING,
                                    QueueStatus::CALLED,
                                ],
                                true
                            )
                    )
                    ->requiresConfirmation()
                    ->action(
                        fn (ServiceQueue $record) =>
                            self::runAction(
                                fn () => app(QueueFifoService::class)
                                    ->cancel($record),
                                'Antrean '.$record->queue_number.' dibatalkan.'
                            )
                    ),
            ])
            ->toolbarActions([])
            ->emptyStateHeading('Belum ada antrean pelayanan')
            ->emptyStateDescription(
                'Antrean yang diambil masyarakat akan ditampilkan pada halaman ini.'
            )
            ->emptyStateIcon('heroicon-o-queue-list');
    }

    private static function runAction(
        callable $callback,
        string $successMessage,
    ): void {
        try {
            $callback();

            Notification::make()
                ->title($successMessage)
                ->success()
                ->send();
        } catch (Throwable $e) {
            Notification::make()
                ->title('Proses FIFO ditolak')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}

<?php

namespace App\Filament\Resources\ServiceQueues\Tables;

use App\Enums\QueueStatus;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServiceQueuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('registered_at', 'desc')
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

                TextColumn::make('application.registration_number')
                    ->label('Nomor Registrasi')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

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
                    ->searchable()
                    ->sortable(),

                TextColumn::make('queue_date')
                    ->label('Tanggal Antrean')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('sequence')
                    ->label('Nomor Urut')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('registered_at')
                    ->label('Waktu Pendaftaran')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('called_at')
                    ->label('Waktu Dipanggil')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('service_started_at')
                    ->label('Mulai Dilayani')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('served_at')
                    ->label('Selesai Dilayani')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('cancelled_at')
                    ->label('Waktu Dibatalkan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Antrean')
                    ->options(QueueStatus::class),

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
            ])
            ->toolbarActions([])
            ->emptyStateHeading('Belum ada antrean pelayanan')
            ->emptyStateDescription('Antrean yang diambil masyarakat akan ditampilkan pada halaman ini.')
            ->emptyStateIcon('heroicon-o-queue-list');
    }
}
<?php

namespace App\Filament\Pimpinan\Resources\Sections\Tables;

use App\Models\Section;
use App\Models\ServiceApplication;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('name')
                    ->label('Nama Seksi')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('semibold'),

                TextColumn::make('employee_count')
                    ->label('Jumlah Pegawai')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('services_total')
                    ->label('Jumlah Layanan')
                    ->state(
                        fn (Section $record): int =>
                            $record->services()->count(),
                    )
                    ->alignCenter(),

                TextColumn::make('applications_total')
                    ->label('Total Permohonan')
                    ->state(
                        fn (Section $record): int =>
                            ServiceApplication::query()
                                ->whereHas(
                                    'service',
                                    fn ($query) =>
                                        $query->where(
                                            'section_id',
                                            $record->id,
                                        ),
                                )
                                ->count(),
                    )
                    ->alignCenter()
                    ->color('primary')
                    ->weight('bold'),

                TextColumn::make('queues_total')
                    ->label('Total Antrean')
                    ->state(
                        fn (Section $record): int =>
                            $record->queues()->count(),
                    )
                    ->alignCenter(),

                TextColumn::make('daily_queue_quota')
                    ->label('Kuota Antrean Harian')
                    ->numeric()
                    ->placeholder('Tidak menggunakan antrean')
                    ->alignCenter(),

                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label('Status Seksi')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
            ])
            ->toolbarActions([])
            ->defaultSort('name')
            ->paginationPageOptions([5, 10, 25])
            ->emptyStateHeading('Belum ada data seksi')
            ->emptyStateDescription(
                'Data lima seksi Kantor Camat Panakkukang akan tampil di sini.',
            )
            ->emptyStateIcon('heroicon-o-building-office-2');
    }
}

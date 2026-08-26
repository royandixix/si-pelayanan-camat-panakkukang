<?php

namespace App\Filament\Resources\ResearchDatasetRecords\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ResearchDatasetRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('record_date', 'asc')
            ->columns([
                TextColumn::make('source_row_no')
                    ->label('No. Sumber')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('dataset_name')
                    ->label('Dataset')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('section.name')
                    ->label('Seksi')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('service.name')
                    ->label('Jenis Layanan')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('record_date')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable(),

                TextColumn::make('subject_name')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->placeholder('-')
                    ->wrap(),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->searchable()
                    ->placeholder('-')
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('source_file')
                    ->label('Sumber')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('validation_status')
                    ->label('Validasi')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'valid' => 'Valid',
                            'needs_review' => 'Perlu Verifikasi',
                            default => $state,
                        }
                    )
                    ->color(
                        fn (string $state): string => match ($state) {
                            'valid' => 'success',
                            'needs_review' => 'warning',
                            default => 'gray',
                        }
                    ),
            ])
            ->filters([
                SelectFilter::make('dataset_name')
                    ->label('Dataset')
                    ->options([
                        'Pewarisan' => 'Pewarisan',
                        'Izin Meneliti' => 'Izin Meneliti',
                        'Rekomendasi Kegiatan' => 'Rekomendasi Kegiatan',
                    ]),

                SelectFilter::make('section_id')
                    ->label('Seksi')
                    ->relationship('section', 'name'),

                SelectFilter::make('service_id')
                    ->label('Jenis Layanan')
                    ->relationship('service', 'name'),

                SelectFilter::make('validation_status')
                    ->label('Status Validasi')
                    ->options([
                        'valid' => 'Valid',
                        'needs_review' => 'Perlu Verifikasi',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),
            ])
            ->toolbarActions([])
            ->paginated([
                10,
                25,
                50,
                100,
            ])
            ->defaultPaginationPageOption(25);
    }
}

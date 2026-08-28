<?php

namespace App\Filament\Resources\KMeansRuns\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KMeansRunsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Run Final')
                    ->formatStateUsing(
                        fn ($state): string => '#'.$state
                    )
                    ->sortable(),

                TextColumn::make('k')
                    ->label('K')
                    ->numeric(),

                TextColumn::make('total_source_records')
                    ->label('Data Sumber')
                    ->numeric(),

                TextColumn::make('valid_source_records')
                    ->label('Data Valid')
                    ->numeric(),

                TextColumn::make('excluded_records')
                    ->label('Dikeluarkan')
                    ->numeric(),

                TextColumn::make('total_points')
                    ->label('Titik K-Means')
                    ->numeric(),

                TextColumn::make('iterations')
                    ->label('Iterasi')
                    ->numeric(),

                TextColumn::make('wcss')
                    ->label('WCSS')
                    ->numeric(decimalPlaces: 8),

                TextColumn::make('silhouette_score')
                    ->label('Silhouette')
                    ->numeric(decimalPlaces: 6),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state): string => match ($state) {
                            'completed' => 'Selesai',
                            'processing' => 'Diproses',
                            'failed' => 'Gagal',
                            default => ucfirst((string) $state),
                        }
                    )
                    ->color(
                        fn ($state): string => match ($state) {
                            'completed' => 'success',
                            'processing' => 'warning',
                            'failed' => 'danger',
                            default => 'gray',
                        }
                    ),

                TextColumn::make('processed_at')
                    ->label('Waktu Proses')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'completed' => 'Selesai',
                        'processing' => 'Diproses',
                        'failed' => 'Gagal',
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
            ])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('Belum ada proses K-Means')
            ->emptyStateDescription(
                'Jalankan proses K-Means untuk menghasilkan clustering data pelayanan.'
            )
            ->emptyStateIcon('heroicon-o-chart-bar-square');
    }
}

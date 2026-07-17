<?php

namespace App\Filament\Pimpinan\Resources\KMeansResults\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KMeansResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('section.name')
                    ->label('Nama Seksi')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->placeholder('-'),

                TextColumn::make('total_applications')
                    ->label('Jumlah Permohonan')
                    ->numeric()
                    ->placeholder('0'),

                TextColumn::make('average_completion_time')
                    ->label('Rata-rata Waktu')
                    ->suffix(' hari')
                    ->placeholder('0'),

                TextColumn::make('employee_count')
                    ->label('Jumlah Pegawai')
                    ->numeric()
                    ->placeholder('0'),

                TextColumn::make('cluster')
                    ->label('Cluster')
                    ->badge()
                    ->formatStateUsing(
                        fn(mixed $state): string=>$state===null
                            ? '-'
                            : 'Cluster '.(string)$state,
                    )
                    ->color(function(mixed $state): string {
                        return match((string)$state){
                            '0'=>'success',
                            '1'=>'warning',
                            '2'=>'danger',
                            default=>'gray',
                        };
                    }),

                TextColumn::make('workload_category')
                    ->label('Kategori Beban')
                    ->badge()
                    ->formatStateUsing(
                        fn(mixed $state): string=>$state
                            ? str((string)$state)->replace('_',' ')->title()->toString()
                            : '-',
                    )
                    ->color(function(mixed $state): string {
                        $value=strtolower((string)$state);

                        return match(true){
                            str_contains($value,'tinggi')=>'danger',
                            str_contains($value,'sedang')=>'warning',
                            str_contains($value,'rendah')=>'success',
                            default=>'gray',
                        };
                    }),

                TextColumn::make('recommendation')
                    ->label('Rekomendasi')
                    ->limit(80)
                    ->wrap()
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Tanggal Analisis')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                SelectFilter::make('cluster')
                    ->label('Cluster')
                    ->options([
                        '0'=>'Cluster 0',
                        '1'=>'Cluster 1',
                        '2'=>'Cluster 2',
                    ]),

                SelectFilter::make('section_id')
                    ->label('Seksi')
                    ->relationship('section','name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat Hasil')
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
            ])
            ->toolbarActions([])
            ->defaultSort('created_at','desc')
            ->paginationPageOptions([10,25,50])
            ->emptyStateHeading('Belum ada hasil K-Means')
            ->emptyStateDescription('Jalankan proses K-Means dari panel Super Admin untuk menghasilkan analisis.')
            ->emptyStateIcon('heroicon-o-chart-bar-square');
    }
}
<?php

namespace App\Filament\Resources\KMeansRuns\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use stdClass;

class KMeansRunsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('nomor')
                    ->label('No.')
                    ->state(
                        static function (
                            HasTable $livewire,
                            stdClass $rowLoop,
                        ): string {
                            return (string) (
                                $rowLoop->iteration
                                + (
                                    $livewire->getTableRecordsPerPage()
                                    * ($livewire->getTablePage() - 1)
                                )
                            );
                        },
                    ),

                TextColumn::make('period_start')
                    ->label('Tanggal Awal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('period_end')
                    ->label('Tanggal Akhir')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('cluster_count')
                    ->label('Jumlah Klaster')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('iterations')
                    ->label('Iterasi')
                    ->numeric()
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('wcss')
                    ->label('WCSS')
                    ->numeric(decimalPlaces: 6)
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('silhouette_score')
                    ->label('Silhouette Score')
                    ->numeric(decimalPlaces: 6)
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('davies_bouldin_index')
                    ->label('Davies-Bouldin Index')
                    ->numeric(decimalPlaces: 6)
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('executor.name')
                    ->label('Dijalankan Oleh')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('executed_at')
                    ->label('Waktu Pelaksanaan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

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
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'processing' => 'Sedang Diproses',
                        'completed' => 'Selesai',
                        'failed' => 'Gagal',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),
                EditAction::make()
                    ->label('Ubah'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus yang dipilih'),
                ])
                    ->label('Tindakan'),
            ])
            ->emptyStateHeading('Belum ada proses K-Means')
            ->emptyStateDescription(
                'Buat proses K-Means untuk melakukan klasterisasi beban kerja setiap seksi.',
            )
            ->emptyStateIcon('heroicon-o-chart-bar-square');
    }
}
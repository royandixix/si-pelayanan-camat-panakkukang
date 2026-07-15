<?php

namespace App\Filament\Resources\KMeansRuns\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KMeansRunInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('period_start')
                    ->label('Tanggal Awal Periode')
                    ->date('d M Y'),

                TextEntry::make('period_end')
                    ->label('Tanggal Akhir Periode')
                    ->date('d M Y'),

                TextEntry::make('cluster_count')
                    ->label('Jumlah Klaster')
                    ->numeric(),

                TextEntry::make('status')
                    ->label('Status Proses')
                    ->badge(),

                TextEntry::make('iterations')
                    ->label('Jumlah Iterasi')
                    ->numeric()
                    ->placeholder('-'),

                TextEntry::make('wcss')
                    ->label('Nilai WCSS')
                    ->numeric(decimalPlaces: 6)
                    ->placeholder('-'),

                TextEntry::make('silhouette_score')
                    ->label('Nilai Silhouette Score')
                    ->numeric(decimalPlaces: 6)
                    ->placeholder('-'),

                TextEntry::make('davies_bouldin_index')
                    ->label('Nilai Davies-Bouldin Index')
                    ->numeric(decimalPlaces: 6)
                    ->placeholder('-'),

                TextEntry::make('input_snapshot')
                    ->label('Data Masukan')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('executor.name')
                    ->label('Dijalankan Oleh')
                    ->placeholder('-'),

                TextEntry::make('executed_at')
                    ->label('Waktu Pelaksanaan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextEntry::make('error_message')
                    ->label('Pesan Kesalahan')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('notes')
                    ->label('Catatan')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),
            ]);
    }
}
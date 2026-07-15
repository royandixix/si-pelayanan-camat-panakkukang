<?php

namespace App\Filament\Resources\KMeansRuns\Schemas;

use App\Enums\KMeansRunStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class KMeansRunForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('period_start')
                    ->label('Tanggal Awal Periode')
                    ->native(false)
                    ->displayFormat('d M Y')
                    ->required(),

                DatePicker::make('period_end')
                    ->label('Tanggal Akhir Periode')
                    ->native(false)
                    ->displayFormat('d M Y')
                    ->afterOrEqual('period_start')
                    ->required(),

                TextInput::make('cluster_count')
                    ->label('Jumlah Klaster')
                    ->numeric()
                    ->minValue(2)
                    ->maxValue(5)
                    ->default(3)
                    ->required(),

                Select::make('status')
                    ->label('Status Proses')
                    ->options(KMeansRunStatus::class)
                    ->default('pending')
                    ->required(),

                TextInput::make('iterations')
                    ->label('Jumlah Iterasi')
                    ->numeric()
                    ->minValue(0)
                    ->placeholder('-'),

                TextInput::make('wcss')
                    ->label('Nilai WCSS')
                    ->numeric()
                    ->placeholder('-'),

                TextInput::make('silhouette_score')
                    ->label('Nilai Silhouette Score')
                    ->numeric()
                    ->placeholder('-'),

                TextInput::make('davies_bouldin_index')
                    ->label('Nilai Davies-Bouldin Index')
                    ->numeric()
                    ->placeholder('-'),

                Textarea::make('input_snapshot')
                    ->label('Data Masukan')
                    ->rows(6)
                    ->placeholder('-')
                    ->columnSpanFull(),

                Select::make('executed_by')
                    ->label('Dijalankan Oleh')
                    ->relationship('executor', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Belum dijalankan'),

                DateTimePicker::make('executed_at')
                    ->label('Waktu Pelaksanaan')
                    ->native(false)
                    ->displayFormat('d M Y H:i')
                    ->seconds(false),

                Textarea::make('error_message')
                    ->label('Pesan Kesalahan')
                    ->rows(4)
                    ->placeholder('-')
                    ->columnSpanFull(),

                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(4)
                    ->placeholder('-')
                    ->columnSpanFull(),
            ]);
    }
}
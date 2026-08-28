<?php

namespace App\Filament\Resources\KMeansResults\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KMeansResultForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hasil K-Means')
                    ->schema([
                        TextInput::make('dataset_name')
                            ->label('Dataset')
                            ->disabled(),

                        TextInput::make('year')
                            ->label('Tahun')
                            ->disabled(),

                        TextInput::make('month')
                            ->label('Bulan')
                            ->disabled(),

                        TextInput::make('jumlah_pelayanan')
                            ->label('Jumlah Pelayanan')
                            ->disabled(),

                        TextInput::make('hari_aktif')
                            ->label('Hari Aktif')
                            ->disabled(),

                        TextInput::make('cluster')
                            ->label('Cluster')
                            ->disabled(),

                        TextInput::make('cluster_label')
                            ->label('Kategori K-Means')
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('Label Referensi Berbasis Aturan')
                    ->schema([
                        Select::make('reference_label')
                            ->label('Label Referensi')
                            ->options([
                                'Rendah' => 'Rendah',
                                'Sedang' => 'Sedang',
                                'Tinggi' => 'Tinggi',
                            ])
                            ->native(false)
                            ->required()
                            ->helperText(
                                'Pada pengujian penelitian ini, label referensi menggunakan aturan berbasis data (Z-Score dan tercile) dan tidak disalin dari hasil K-Means.'
                            ),
                    ]),
            ]);
    }
}

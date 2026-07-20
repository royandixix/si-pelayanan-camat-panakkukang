<?php

namespace App\Filament\Resources\Sections\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Seksi')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),

                TextInput::make('name')
                    ->label('Nama Seksi')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(4)
                    ->placeholder('Masukkan deskripsi seksi')
                    ->columnSpanFull(),

                TextInput::make('employee_count')
                    ->label('Jumlah Pegawai')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                TextInput::make('daily_queue_quota')
                    ->label('Kuota Antrean Harian')
                    ->numeric()
                    ->minValue(1)
                    ->placeholder('Tidak menggunakan antrean')
                    ->helperText('Kosongkan jika seksi tidak menggunakan antrean digital.'),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}
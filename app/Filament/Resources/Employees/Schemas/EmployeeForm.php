<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Data Pegawai')
                    ->description('Lengkapi informasi pegawai Kecamatan Panakkukang.')
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->placeholder('Masukkan nama lengkap pegawai')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('position')
                            ->label('Jabatan')
                            ->placeholder('Masukkan jabatan pegawai')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('work_unit')
                            ->label('Unit Kerja')
                            ->placeholder('Contoh: Seksi Pemerintahan')
                            ->maxLength(255),

                        TextInput::make('display_order')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),

                        FileUpload::make('photo')
                            ->label('Foto Pegawai')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('employees')
                            ->visibility('public')
                            ->maxSize(25600)
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Tampilkan di Website')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
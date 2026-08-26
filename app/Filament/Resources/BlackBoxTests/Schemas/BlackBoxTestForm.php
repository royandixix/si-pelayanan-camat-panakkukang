<?php

namespace App\Filament\Resources\BlackBoxTests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlackBoxTestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Skenario Pengujian')
                    ->schema([
                        TextInput::make('code')
                            ->label('Kode')
                            ->disabled(),

                        TextInput::make('module')
                            ->label('Modul')
                            ->disabled(),

                        Textarea::make('scenario')
                            ->label('Skenario')
                            ->disabled(),

                        Textarea::make('test_input')
                            ->label('Input Pengujian')
                            ->disabled(),

                        Textarea::make('expected_result')
                            ->label('Hasil yang Diharapkan')
                            ->disabled(),
                    ]),

                Section::make('Hasil Pengujian')
                    ->schema([
                        Textarea::make('actual_result')
                            ->label('Hasil Aktual')
                            ->rows(4)
                            ->required(),

                        Select::make('status')
                            ->label('Status Pengujian')
                            ->options([
                                'belum_diuji' => 'Belum Diuji',
                                'lulus' => 'Lulus',
                                'gagal' => 'Gagal',
                            ])
                            ->native(false)
                            ->required(),
                    ]),
            ]);
    }
}

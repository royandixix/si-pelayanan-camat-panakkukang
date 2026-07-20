<?php

namespace App\Filament\Resources\ServiceRequirements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceRequirementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_id')
                    ->label('Jenis Layanan')
                    ->relationship(
                        name: 'service',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('is_active', true)
                            ->orderBy('name'),
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('name')
                    ->label('Nama Persyaratan')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(4)
                    ->placeholder('Masukkan deskripsi persyaratan')
                    ->columnSpanFull(),

                TagsInput::make('allowed_extensions')
                    ->label('Ekstensi Berkas yang Diizinkan')
                    ->placeholder('Tambahkan ekstensi')
                    ->suggestions([
                        'pdf',
                        'jpg',
                        'jpeg',
                        'png',
                    ])
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('max_size_kb')
                    ->label('Ukuran Maksimal Berkas')
                    ->numeric()
                    ->minValue(1)
                    ->default(2048)
                    ->suffix('KB')
                    ->required(),

                Toggle::make('is_required')
                    ->label('Wajib Diunggah')
                    ->default(true)
                    ->required(),

                TextInput::make('sort_order')
                    ->label('Urutan Tampilan')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
            ]);
    }
}
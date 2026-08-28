<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Data Galeri')
                    ->description(
                        'Kelola foto kegiatan yang tampil pada website publik.'
                    )
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Kegiatan')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('Contoh: Pelayanan')
                            ->maxLength(100),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->label('Foto Kegiatan')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('galleries')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->maxSize(10240)
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('display_order')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Tampilkan di Website')
                            ->default(true),
                    ]),
            ]);
    }
}

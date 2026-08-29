<?php

namespace App\Filament\Resources\Beritas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BeritaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Data Berita')
                    ->description(
                        'Kelola berita dan informasi yang tampil pada website publik.'
                    )
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Berita')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder(
                                'Pelayanan, Informasi, Kegiatan'
                            )
                            ->maxLength(100),

                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publikasi')
                            ->default(now())
                            ->seconds(false)
                            ->required(),

                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->rows(4)
                            ->maxLength(700)
                            ->columnSpanFull(),

                        Textarea::make('content')
                            ->label('Isi Berita')
                            ->rows(12)
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->label('Gambar Berita')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('berita')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->maxSize(10240)
                            ->columnSpanFull(),

                        Toggle::make('is_featured')
                            ->label('Berita Utama')
                            ->default(false),

                        Toggle::make('is_active')
                            ->label('Tampilkan di Website')
                            ->default(true),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('section_id')
                    ->label('Seksi Penanggung Jawab')
                    ->relationship(
                        name: 'section',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('is_active', true)
                            ->orderBy('name'),
                    )
                    ->searchable()
                    ->preload()
                    ->helperText('Layanan yang dibuat akan otomatis menjadi layanan pada seksi yang dipilih.')
                    ->required(),

                TextInput::make('code')
                    ->label('Kode Layanan')
                    ->required()
                    ->maxLength(40)
                    ->unique(ignoreRecord: true)
                    ->dehydrateStateUsing(
                        fn (?string $state): ?string => filled($state)
                            ? Str::upper(Str::snake(trim($state)))
                            : null,
                    ),

                TextInput::make('name')
                    ->label('Nama Layanan')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn (?string $state, callable $set) => $set(
                            'slug',
                            Str::slug($state ?? ''),
                        ),
                    ),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Digunakan sebagai alamat halaman layanan.'),

                Textarea::make('description')
                    ->label('Deskripsi Layanan')
                    ->rows(4)
                    ->placeholder('Masukkan informasi mengenai layanan')
                    ->columnSpanFull(),

                Textarea::make('form_schema')
                    ->label('Struktur Formulir')
                    ->rows(8)
                    ->placeholder('Masukkan struktur formulir dalam format JSON')
                    ->helperText('Kosongkan jika formulir layanan belum dikonfigurasi.')
                    ->columnSpanFull(),

                Toggle::make('queue_enabled')
                    ->label('Menggunakan Antrean Digital')
                    ->helperText('Aktifkan hanya untuk layanan yang menggunakan antrean.')
                    ->default(false)
                    ->required(),

                TextInput::make('processing_days')
                    ->label('Estimasi Waktu Penyelesaian')
                    ->numeric()
                    ->minValue(1)
                    ->suffix('hari')
                    ->placeholder('Tidak ditentukan'),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}
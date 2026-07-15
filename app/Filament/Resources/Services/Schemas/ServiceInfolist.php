<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ServiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('section.name')
                    ->label('Seksi Penanggung Jawab'),

                TextEntry::make('code')
                    ->label('Kode Layanan')
                    ->badge(),

                TextEntry::make('name')
                    ->label('Nama Layanan'),

                TextEntry::make('slug')
                    ->label('Slug')
                    ->copyable(),

                TextEntry::make('description')
                    ->label('Deskripsi Layanan')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('form_schema')
                    ->label('Struktur Formulir')
                    ->placeholder('-')
                    ->columnSpanFull(),

                IconEntry::make('queue_enabled')
                    ->label('Menggunakan Antrean Digital')
                    ->boolean(),

                TextEntry::make('processing_days')
                    ->label('Estimasi Penyelesaian')
                    ->numeric()
                    ->suffix(' hari')
                    ->placeholder('Tidak ditentukan'),

                IconEntry::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),

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
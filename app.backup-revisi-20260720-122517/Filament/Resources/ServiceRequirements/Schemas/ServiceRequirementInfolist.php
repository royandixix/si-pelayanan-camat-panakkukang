<?php

namespace App\Filament\Resources\ServiceRequirements\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ServiceRequirementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('service.name')
                    ->label('Jenis Layanan'),

                TextEntry::make('service.section.name')
                    ->label('Seksi')
                    ->placeholder('-'),

                TextEntry::make('name')
                    ->label('Nama Persyaratan'),

                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('allowed_extensions')
                    ->label('Ekstensi Berkas')
                    ->badge()
                    ->separator(',')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('max_size_kb')
                    ->label('Ukuran Maksimal')
                    ->numeric()
                    ->suffix(' KB'),

                IconEntry::make('is_required')
                    ->label('Wajib Diunggah')
                    ->boolean(),

                TextEntry::make('sort_order')
                    ->label('Urutan Tampilan')
                    ->numeric(),

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
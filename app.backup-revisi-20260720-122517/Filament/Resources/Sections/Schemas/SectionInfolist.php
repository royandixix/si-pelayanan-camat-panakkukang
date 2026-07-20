<?php

namespace App\Filament\Resources\Sections\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SectionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code')
                    ->label('Kode Seksi')
                    ->badge(),

                TextEntry::make('name')
                    ->label('Nama Seksi'),

                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('employee_count')
                    ->label('Jumlah Pegawai')
                    ->numeric(),

                TextEntry::make('daily_queue_quota')
                    ->label('Kuota Antrean Harian')
                    ->numeric()
                    ->placeholder('Tidak menggunakan antrean'),

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
<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nama Lengkap'),

                TextEntry::make('role')
                    ->label('Peran')
                    ->badge(),

                TextEntry::make('section.name')
                    ->label('Seksi')
                    ->placeholder('Tidak memiliki seksi'),

                TextEntry::make('nik')
                    ->label('NIK')
                    ->placeholder('-')
                    ->copyable(),

                TextEntry::make('email')
                    ->label('Alamat Email')
                    ->copyable(),

                TextEntry::make('phone')
                    ->label('Nomor Telepon')
                    ->placeholder('-')
                    ->copyable(),

                TextEntry::make('address')
                    ->label('Alamat')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('email_verified_at')
                    ->label('Email Diverifikasi Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum diverifikasi'),

                IconEntry::make('is_active')
                    ->label('Akun Aktif')
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
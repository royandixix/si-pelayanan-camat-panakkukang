<?php

namespace App\Filament\Resources\ServiceQueues\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ServiceQueueInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('application.registration_number')
                    ->label('Nomor Registrasi')
                    ->placeholder('-')
                    ->copyable(),

                TextEntry::make('user.name')
                    ->label('Nama Masyarakat'),

                TextEntry::make('section.name')
                    ->label('Seksi'),

                TextEntry::make('service.name')
                    ->label('Jenis Layanan'),

                TextEntry::make('queue_date')
                    ->label('Tanggal Antrean')
                    ->date('d M Y'),

                TextEntry::make('prefix')
                    ->label('Awalan Nomor'),

                TextEntry::make('sequence')
                    ->label('Nomor Urut')
                    ->numeric(),

                TextEntry::make('queue_number')
                    ->label('Nomor Antrean')
                    ->badge()
                    ->copyable(),

                TextEntry::make('status')
                    ->label('Status Antrean')
                    ->badge(),

                TextEntry::make('registered_at')
                    ->label('Waktu Pendaftaran')
                    ->dateTime('d M Y H:i'),

                TextEntry::make('called_at')
                    ->label('Waktu Dipanggil')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextEntry::make('service_started_at')
                    ->label('Waktu Mulai Dilayani')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextEntry::make('served_at')
                    ->label('Waktu Selesai Dilayani')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextEntry::make('cancelled_at')
                    ->label('Waktu Dibatalkan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

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
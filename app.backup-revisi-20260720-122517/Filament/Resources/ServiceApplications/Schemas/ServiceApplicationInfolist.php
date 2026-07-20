<?php

namespace App\Filament\Resources\ServiceApplications\Schemas;

use App\Models\ServiceApplication;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ServiceApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('registration_number')
                    ->label('Nomor Registrasi')
                    ->copyable(),

                TextEntry::make('user.name')
                    ->label('Nama Masyarakat'),

                TextEntry::make('service.name')
                    ->label('Jenis Layanan'),

                TextEntry::make('service.section.name')
                    ->label('Seksi'),

                TextEntry::make('assignedAdmin.name')
                    ->label('Admin Seksi')
                    ->placeholder('Belum ditentukan'),

                TextEntry::make('status')
                    ->label('Status Permohonan')
                    ->badge(),

                TextEntry::make('applicant_data')
                    ->label('Data Pemohon')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('applicant_notes')
                    ->label('Catatan Pemohon')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('internal_notes')
                    ->label('Catatan Internal')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('submitted_at')
                    ->label('Tanggal Diajukan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextEntry::make('verified_at')
                    ->label('Tanggal Diverifikasi')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextEntry::make('completed_at')
                    ->label('Tanggal Selesai')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextEntry::make('rejected_at')
                    ->label('Tanggal Ditolak')
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

                TextEntry::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->visible(
                        fn (ServiceApplication $record): bool =>
                            $record->trashed(),
                    ),
            ]);
    }
}
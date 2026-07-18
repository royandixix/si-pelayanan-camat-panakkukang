<?php

namespace App\Filament\Pimpinan\Resources\ServiceApplications\Schemas;

use App\Enums\ApplicationStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Permohonan')
                    ->description('Informasi utama permohonan pelayanan masyarakat.')
                    ->icon('heroicon-o-document-text')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('registration_number')
                            ->label('Nomor Permohonan')
                            ->copyable()
                            ->weight('bold')
                            ->color('primary'),

                        TextEntry::make('status')
                            ->label('Status Permohonan')
                            ->badge()
                            ->formatStateUsing(function(mixed $state): string {
                                if($state instanceof ApplicationStatus){
                                    return (string)$state->getLabel();
                                }

                                return str((string)$state)
                                    ->replace('_',' ')
                                    ->title()
                                    ->toString();
                            })
                            ->color(function(mixed $state): string|array|null {
                                if($state instanceof ApplicationStatus){
                                    return $state->getColor();
                                }

                                return match((string)$state){
                                    'draft'=>'gray',
                                    'submitted'=>'info',
                                    'verification'=>'warning',
                                    'revision'=>'warning',
                                    'approved'=>'success',
                                    'processing'=>'primary',
                                    'completed'=>'success',
                                    'collected'=>'success',
                                    'rejected'=>'danger',
                                    default=>'gray',
                                };
                            }),

                        TextEntry::make('submitted_at')
                            ->label('Tanggal Pengajuan')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('service.name')
                            ->label('Jenis Layanan')
                            ->placeholder('-'),

                        TextEntry::make('service.section.name')
                            ->label('Seksi Penanggung Jawab')
                            ->placeholder('-'),

                        TextEntry::make('assignedAdmin.name')
                            ->label('Petugas yang Menangani')
                            ->placeholder('Belum ditugaskan'),

                        TextEntry::make('verified_at')
                            ->label('Tanggal Verifikasi')
                            ->dateTime('d M Y H:i')
                            ->placeholder('Belum diverifikasi'),

                        TextEntry::make('completed_at')
                            ->label('Tanggal Selesai')
                            ->dateTime('d M Y H:i')
                            ->placeholder('Belum selesai'),

                        TextEntry::make('rejected_at')
                            ->label('Tanggal Ditolak')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),
                    ]),

                Section::make('Data Pemohon')
                    ->description('Identitas masyarakat yang mengajukan pelayanan.')
                    ->icon('heroicon-o-user')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama Lengkap')
                            ->placeholder('-'),

                        TextEntry::make('user.nik')
                            ->label('NIK')
                            ->copyable()
                            ->placeholder('-'),

                        TextEntry::make('user.email')
                            ->label('Alamat Email')
                            ->copyable()
                            ->placeholder('-'),

                        TextEntry::make('user.phone')
                            ->label('Nomor Telepon')
                            ->copyable()
                            ->placeholder('-'),

                        TextEntry::make('user.address')
                            ->label('Alamat')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Catatan Permohonan')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->columns(1)
                    ->schema([
                        TextEntry::make('applicant_notes')
                            ->label('Catatan Pemohon')
                            ->placeholder('Tidak ada catatan dari pemohon.')
                            ->columnSpanFull(),

                        TextEntry::make('internal_notes')
                            ->label('Catatan Internal Petugas')
                            ->placeholder('Belum ada catatan internal.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

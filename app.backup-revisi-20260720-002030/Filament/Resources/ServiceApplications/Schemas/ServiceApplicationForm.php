<?php

namespace App\Filament\Resources\ServiceApplications\Schemas;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('registration_number')
                    ->label('Nomor Registrasi')
                    ->required()
                    ->maxLength(40)
                    ->unique(ignoreRecord: true),

                Select::make('user_id')
                    ->label('Nama Masyarakat')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('role', UserRole::MASYARAKAT->value)
                            ->where('is_active', true)
                            ->orderBy('name'),
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('service_id')
                    ->label('Jenis Layanan')
                    ->relationship(
                        name: 'service',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('is_active', true)
                            ->orderBy('name'),
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('assigned_admin_id')
                    ->label('Admin Seksi')
                    ->relationship(
                        name: 'assignedAdmin',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('role', UserRole::ADMIN_SEKSI->value)
                            ->where('is_active', true)
                            ->orderBy('name'),
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder('Belum ditentukan'),

                Select::make('status')
                    ->label('Status Permohonan')
                    ->options(ApplicationStatus::class)
                    ->default('draft')
                    ->required(),

                Textarea::make('applicant_data')
                    ->label('Data Pemohon')
                    ->rows(6)
                    ->placeholder('-')
                    ->columnSpanFull(),

                Textarea::make('applicant_notes')
                    ->label('Catatan Pemohon')
                    ->rows(4)
                    ->placeholder('-')
                    ->columnSpanFull(),

                Textarea::make('internal_notes')
                    ->label('Catatan Internal')
                    ->rows(4)
                    ->placeholder('-')
                    ->columnSpanFull(),

                DateTimePicker::make('submitted_at')
                    ->label('Tanggal Diajukan')
                    ->native(false)
                    ->displayFormat('d M Y H:i')
                    ->seconds(false),

                DateTimePicker::make('verified_at')
                    ->label('Tanggal Diverifikasi')
                    ->native(false)
                    ->displayFormat('d M Y H:i')
                    ->seconds(false),

                DateTimePicker::make('completed_at')
                    ->label('Tanggal Selesai')
                    ->native(false)
                    ->displayFormat('d M Y H:i')
                    ->seconds(false),

                DateTimePicker::make('rejected_at')
                    ->label('Tanggal Ditolak')
                    ->native(false)
                    ->displayFormat('d M Y H:i')
                    ->seconds(false),
            ]);
    }
}
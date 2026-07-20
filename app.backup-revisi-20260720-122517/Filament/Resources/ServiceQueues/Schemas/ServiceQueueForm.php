<?php

namespace App\Filament\Resources\ServiceQueues\Schemas;

use App\Enums\QueueStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ServiceQueueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('application_id')
                    ->label('Permohonan Layanan')
                    ->relationship('application', 'registration_number')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('user_id')
                    ->label('Nama Masyarakat')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('section_id')
                    ->label('Seksi')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('service_id')
                    ->label('Jenis Layanan')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('queue_date')
                    ->label('Tanggal Antrean')
                    ->native(false)
                    ->displayFormat('d M Y')
                    ->required(),

                TextInput::make('prefix')
                    ->label('Awalan Nomor')
                    ->default('A')
                    ->maxLength(5)
                    ->required(),

                TextInput::make('sequence')
                    ->label('Nomor Urut')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                TextInput::make('queue_number')
                    ->label('Nomor Antrean')
                    ->maxLength(20)
                    ->required(),

                Select::make('status')
                    ->label('Status Antrean')
                    ->options(QueueStatus::class)
                    ->default('waiting')
                    ->required(),

                DateTimePicker::make('registered_at')
                    ->label('Waktu Pendaftaran')
                    ->native(false)
                    ->displayFormat('d M Y H:i')
                    ->seconds(false)
                    ->required(),

                DateTimePicker::make('called_at')
                    ->label('Waktu Dipanggil')
                    ->native(false)
                    ->displayFormat('d M Y H:i')
                    ->seconds(false),

                DateTimePicker::make('service_started_at')
                    ->label('Waktu Mulai Dilayani')
                    ->native(false)
                    ->displayFormat('d M Y H:i')
                    ->seconds(false),

                DateTimePicker::make('served_at')
                    ->label('Waktu Selesai Dilayani')
                    ->native(false)
                    ->displayFormat('d M Y H:i')
                    ->seconds(false),

                DateTimePicker::make('cancelled_at')
                    ->label('Waktu Dibatalkan')
                    ->native(false)
                    ->displayFormat('d M Y H:i')
                    ->seconds(false),
            ]);
    }
}
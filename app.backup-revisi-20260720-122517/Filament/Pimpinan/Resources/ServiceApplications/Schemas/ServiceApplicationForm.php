<?php

namespace App\Filament\Pimpinan\Resources\ServiceApplications\Schemas;

use App\Enums\ApplicationStatus;
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
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->required(),
                Select::make('assigned_admin_id')
                    ->relationship('assignedAdmin', 'name')
                    ->default(null),
                Select::make('status')
                    ->options(ApplicationStatus::class)
                    ->default('draft')
                    ->required(),
                Textarea::make('applicant_data')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('applicant_notes')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('internal_notes')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('submitted_at'),
                DateTimePicker::make('verified_at'),
                DateTimePicker::make('completed_at'),
                DateTimePicker::make('rejected_at'),
            ]);
    }
}

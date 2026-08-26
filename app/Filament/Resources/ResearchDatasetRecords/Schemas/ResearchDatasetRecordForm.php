<?php

namespace App\Filament\Resources\ResearchDatasetRecords\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ResearchDatasetRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('section_id')
                    ->relationship('section', 'name')
                    ->default(null),
                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->default(null),
                TextInput::make('dataset_name')
                    ->required(),
                TextInput::make('source_file')
                    ->required(),
                TextInput::make('source_row_no')
                    ->numeric()
                    ->default(null),
                DatePicker::make('record_date'),
                TextInput::make('raw_date')
                    ->default(null),
                TextInput::make('subject_name')
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('validation_status')
                    ->required()
                    ->default('valid'),
            ]);
    }
}

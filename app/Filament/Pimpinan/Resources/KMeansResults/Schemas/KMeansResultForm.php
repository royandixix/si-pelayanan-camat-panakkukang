<?php

namespace App\Filament\Pimpinan\Resources\KMeansResults\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class KMeansResultForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('k_means_run_id')
                    ->required()
                    ->numeric(),
                Select::make('section_id')
                    ->relationship('section', 'name')
                    ->required(),
                TextInput::make('service_volume')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('queue_volume')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_volume')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('employee_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('cluster_number')
                    ->required()
                    ->numeric(),
                TextInput::make('centroid')
                    ->required()
                    ->numeric(),
                TextInput::make('distance_to_centroid')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('workload_category')
                    ->required(),
                TextInput::make('rank')
                    ->numeric()
                    ->default(null),
                TextInput::make('recommended_employee_change')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('recommendation')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}

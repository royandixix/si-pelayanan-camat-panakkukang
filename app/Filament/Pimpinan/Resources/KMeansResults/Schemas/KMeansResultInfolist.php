<?php

namespace App\Filament\Pimpinan\Resources\KMeansResults\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KMeansResultInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('k_means_run_id')
                    ->numeric(),
                TextEntry::make('section.name')
                    ->label('Section'),
                TextEntry::make('service_volume')
                    ->numeric(),
                TextEntry::make('queue_volume')
                    ->numeric(),
                TextEntry::make('total_volume')
                    ->numeric(),
                TextEntry::make('employee_count')
                    ->numeric(),
                TextEntry::make('cluster_number')
                    ->numeric(),
                TextEntry::make('centroid')
                    ->numeric(),
                TextEntry::make('distance_to_centroid')
                    ->numeric(),
                TextEntry::make('workload_category'),
                TextEntry::make('rank')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('recommended_employee_change')
                    ->numeric(),
                TextEntry::make('recommendation')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

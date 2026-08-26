<?php

namespace App\Filament\Resources\BlackBoxTests\Pages;

use App\Filament\Resources\BlackBoxTests\BlackBoxTestResource;
use App\Filament\Widgets\BlackBoxStats;
use Filament\Resources\Pages\ListRecords;

class ListBlackBoxTests extends ListRecords
{
    protected static string $resource =
        BlackBoxTestResource::class;

    protected static ?string $title =
        'Black Box Testing';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BlackBoxStats::class,
        ];
    }
}

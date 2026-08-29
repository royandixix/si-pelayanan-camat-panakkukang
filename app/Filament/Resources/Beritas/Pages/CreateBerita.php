<?php

namespace App\Filament\Resources\Beritas\Pages;

use App\Filament\Resources\Beritas\BeritaResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateBerita extends CreateRecord
{
    protected static string $resource = BeritaResource::class;

    protected static ?string $title = 'Tambah Berita';

    protected Width|string|null $maxContentWidth = Width::Full;
}

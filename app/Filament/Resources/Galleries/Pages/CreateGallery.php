<?php

namespace App\Filament\Resources\Galleries\Pages;

use App\Filament\Resources\Galleries\GalleryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateGallery extends CreateRecord
{
    protected static string $resource = GalleryResource::class;

    protected static ?string $title = 'Tambah Galeri';

    protected Width|string|null $maxContentWidth = Width::Full;
}

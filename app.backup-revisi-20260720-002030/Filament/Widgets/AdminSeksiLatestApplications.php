<?php

namespace App\Filament\Widgets;

use App\Models\ServiceApplication;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class AdminSeksiLatestApplications extends BaseWidget
{
    protected static ?string $heading = 'Permohonan Terbaru';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = '30s';

    public function table(Table $table): Table
    {
        $sectionId = auth()->user()?->section_id;

        return $table
            ->query(
                ServiceApplication::query()
                    ->with(['user', 'service'])
                    ->whereHas(
                        'service',
                        fn (Builder $query): Builder => $query->where('section_id', $sectionId),
                    )
                    ->latest('created_at'),
            )
            ->columns([
                TextColumn::make('registration_number')
                    ->label('Nomor Registrasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Masyarakat')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('service.name')
                    ->label('Layanan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('submitted_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultPaginationPageOption(10);
    }

    public static function canView(): bool
    {
        return auth()->user()?->isAdminSeksi() ?? false;
    }
}
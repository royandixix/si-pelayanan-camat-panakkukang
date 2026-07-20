<?php

namespace App\Filament\Pimpinan\Resources\ServiceApplications\RelationManagers;

use App\Enums\ApplicationStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StatusHistoriesRelationManager extends RelationManager
{
    protected static string $relationship='statusHistories';

    protected static ?string $title='Riwayat Status';

    protected static string|\BackedEnum|null $icon='heroicon-o-clock';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('from_status')
                    ->label('Status Sebelumnya')
                    ->badge()
                    ->formatStateUsing(
                        fn(mixed $state): string=>$this->formatStatus($state),
                    )
                    ->color(
                        fn(mixed $state): string=>$this->statusColor($state),
                    )
                    ->placeholder('-'),

                TextColumn::make('to_status')
                    ->label('Status Baru')
                    ->badge()
                    ->formatStateUsing(
                        fn(mixed $state): string=>$this->formatStatus($state),
                    )
                    ->color(
                        fn(mixed $state): string=>$this->statusColor($state),
                    ),

                TextColumn::make('changer.name')
                    ->label('Diubah Oleh')
                    ->placeholder('Sistem'),

                TextColumn::make('notes')
                    ->label('Catatan')
                    ->placeholder('-')
                    ->wrap()
                    ->limit(100),

                TextColumn::make('created_at')
                    ->label('Waktu Perubahan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('created_at','desc')
            ->emptyStateHeading('Belum ada riwayat status')
            ->emptyStateDescription('Perubahan status permohonan akan tampil di sini.')
            ->emptyStateIcon('heroicon-o-clock');
    }

    private function formatStatus(mixed $state): string
    {
        if($state instanceof ApplicationStatus){
            return (string)$state->getLabel();
        }

        if(blank($state)){
            return '-';
        }

        return str((string)$state)
            ->replace('_',' ')
            ->title()
            ->toString();
    }

    private function statusColor(mixed $state): string
    {
        if($state instanceof ApplicationStatus){
            $color=$state->getColor();

            return is_string($color)?$color:'gray';
        }

        return match((string)$state){
            'draft'=>'gray',
            'submitted'=>'info',
            'verification'=>'warning',
            'revision'=>'warning',
            'approved'=>'success',
            'processing'=>'primary',
            'completed'=>'success',
            'collected'=>'success',
            'rejected'=>'danger',
            default=>'gray',
        };
    }

    public static function canViewForRecord(
        Model $ownerRecord,
        string $pageClass,
    ): bool {
        return true;
    }
}

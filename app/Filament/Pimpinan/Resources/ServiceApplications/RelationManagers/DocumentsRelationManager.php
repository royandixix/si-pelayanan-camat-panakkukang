<?php

namespace App\Filament\Pimpinan\Resources\ServiceApplications\RelationManagers;

use App\Enums\DocumentVerificationStatus;
use App\Models\ApplicationDocument;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship='documents';

    protected static ?string $title='Dokumen Persyaratan';

    protected static string|\BackedEnum|null $icon='heroicon-o-paper-clip';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('requirement.name')
                    ->label('Jenis Persyaratan')
                    ->searchable()
                    ->wrap()
                    ->placeholder('-'),

                TextColumn::make('original_name')
                    ->label('Nama File')
                    ->searchable()
                    ->wrap()
                    ->placeholder('-'),

                TextColumn::make('mime_type')
                    ->label('Tipe File')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('size_bytes')
                    ->label('Ukuran')
                    ->formatStateUsing(
                        fn(mixed $state): string=>$state
                            ? number_format(((int)$state)/1024,1).' KB'
                            : '-',
                    )
                    ->toggleable(),

                TextColumn::make('verification_status')
                    ->label('Status Verifikasi')
                    ->badge()
                    ->formatStateUsing(function(mixed $state): string {
                        if($state instanceof DocumentVerificationStatus){
                            return (string)$state->getLabel();
                        }

                        return str((string)$state)
                            ->replace('_',' ')
                            ->title()
                            ->toString();
                    })
                    ->color(function(mixed $state): string|array|null {
                        if($state instanceof DocumentVerificationStatus){
                            return $state->getColor();
                        }

                        return match((string)$state){
                            'pending'=>'warning',
                            'valid'=>'success',
                            'revision'=>'warning',
                            'invalid'=>'danger',
                            default=>'gray',
                        };
                    }),

                TextColumn::make('verification_notes')
                    ->label('Catatan Verifikasi')
                    ->placeholder('-')
                    ->wrap()
                    ->limit(100),

                TextColumn::make('verifier.name')
                    ->label('Diverifikasi Oleh')
                    ->placeholder('-'),

                TextColumn::make('verified_at')
                    ->label('Tanggal Verifikasi')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Tanggal Unggah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('buka')
                    ->label('Buka Dokumen')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('primary')
                    ->url(function(ApplicationDocument $record): ?string {
                        if(blank($record->path)){
                            return null;
                        }

                        return Storage::disk($record->disk?:'public')
                            ->url($record->path);
                    })
                    ->openUrlInNewTab()
                    ->visible(
                        fn(ApplicationDocument $record): bool=>
                            filled($record->path),
                    ),
            ])
            ->toolbarActions([])
            ->defaultSort('created_at','desc')
            ->emptyStateHeading('Belum ada dokumen')
            ->emptyStateDescription('Dokumen persyaratan yang diunggah akan tampil di sini.')
            ->emptyStateIcon('heroicon-o-paper-clip');
    }

    public static function canViewForRecord(
        Model $ownerRecord,
        string $pageClass,
    ): bool {
        return true;
    }
}

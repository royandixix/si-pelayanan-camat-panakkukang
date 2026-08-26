<?php

namespace App\Filament\Resources\ResearchDatasetRecords\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ResearchDatasetRecordInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dataset')
                    ->schema([
                        TextEntry::make('dataset_name')
                            ->label('Nama Dataset'),

                        TextEntry::make('source_row_no')
                            ->label('No. Sumber'),

                        TextEntry::make('section.name')
                            ->label('Seksi')
                            ->placeholder('-'),

                        TextEntry::make('service.name')
                            ->label('Jenis Layanan')
                            ->placeholder('-'),

                        TextEntry::make('record_date')
                            ->label('Tanggal')
                            ->date('d-m-Y')
                            ->placeholder('-'),

                        TextEntry::make('raw_date')
                            ->label('Tanggal Pada Sumber')
                            ->placeholder('-'),

                        TextEntry::make('subject_name')
                            ->label('Nama Pemohon')
                            ->placeholder('-'),

                        TextEntry::make('description')
                            ->label('Keterangan')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('source_file')
                            ->label('File Sumber')
                            ->placeholder('-'),

                        TextEntry::make('validation_status')
                            ->label('Status Validasi')
                            ->badge()
                            ->formatStateUsing(
                                fn (string $state): string => match ($state) {
                                    'valid' => 'Valid',
                                    'needs_review' => 'Perlu Verifikasi',
                                    default => $state,
                                }
                            )
                            ->color(
                                fn (string $state): string => match ($state) {
                                    'valid' => 'success',
                                    'needs_review' => 'warning',
                                    default => 'gray',
                                }
                            ),
                    ])
                    ->columns(2),
            ]);
    }
}

<?php

namespace App\Filament\Resources\ServiceApplications\RelationManagers;

use App\Enums\DocumentVerificationStatus;
use App\Models\ApplicationDocument;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';
    protected static ?string $title = 'Dokumen Persyaratan';
    protected static ?string $modelLabel = 'Dokumen';
    protected static ?string $pluralModelLabel = 'Dokumen Persyaratan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('original_name')
            ->defaultSort('created_at')
            ->columns([
                TextColumn::make('requirement.name')
                    ->label('Persyaratan')
                    ->placeholder('-')
                    ->wrap(),
                TextColumn::make('original_name')
                    ->label('Nama Berkas')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('mime_type')
                    ->label('Tipe Berkas')
                    ->placeholder('-'),
                TextColumn::make('size_bytes')
                    ->label('Ukuran')
                    ->formatStateUsing(fn(?int $state): string => $this->formatFileSize($state ?? 0)),
                TextColumn::make('verification_status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('verifier.name')
                    ->label('Diperiksa Oleh')
                    ->placeholder('-'),
                TextColumn::make('verified_at')
                    ->label('Waktu Pemeriksaan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),
                TextColumn::make('verification_notes')
                    ->label('Catatan')
                    ->placeholder('-')
                    ->limit(40)
                    ->wrap(),
            ])
            ->headerActions([])
            ->recordActions([
                Action::make('openDocument')
                    ->label('Buka')
                    ->icon('heroicon-o-eye')
                    ->url(
                        fn (ApplicationDocument $record): string =>
                            route(
                                'admin.permohonan.dokumen.open',
                                [
                                    'permohonan' => $record->application_id,
                                    'dokumen' => $record->id,
                                ],
                            ),
                    )
                    ->openUrlInNewTab(),
                Action::make('verifyDocument')
                    ->label('Periksa')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->schema([
                        Select::make('verification_status')
                            ->label('Status Dokumen')
                            ->options([
                                DocumentVerificationStatus::VALID->value => DocumentVerificationStatus::VALID->label(),
                                DocumentVerificationStatus::REVISION->value => DocumentVerificationStatus::REVISION->label(),
                                DocumentVerificationStatus::INVALID->value => DocumentVerificationStatus::INVALID->label(),
                            ])
                            ->required()
                            ->live(),
                        Textarea::make('verification_notes')
                            ->label('Catatan Pemeriksaan')
                            ->rows(4)
                            ->required(fn($get): bool => in_array($get('verification_status'), [
                                DocumentVerificationStatus::REVISION->value,
                                DocumentVerificationStatus::INVALID->value,
                            ], true)),
                    ])
                    ->fillForm(fn(ApplicationDocument $record): array => [
                        'verification_status' => $record->verification_status?->value,
                        'verification_notes' => $record->verification_notes,
                    ])
                    ->action(function (ApplicationDocument $record, array $data): void {
                        $application = $record->application;
                        $user = Auth::user();

                        if (!$user instanceof User) {
                            abort(403);
                        }

                        if (
                            !$user->isSuperAdmin()
                            && (
                                !$user->isAdminSeksi()
                                || $application->service?->section_id !== $user->section_id
                            )
                        ) {
                            abort(403);
                        }

                        $record->update([
                            'verification_status' => $data['verification_status'],
                            'verification_notes' => $data['verification_notes'] ?? null,
                            'verified_by' => $user->id,
                            'verified_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Dokumen berhasil diperiksa')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([])
            ->emptyStateHeading('Belum ada dokumen')
            ->emptyStateDescription('Dokumen yang diunggah masyarakat akan tampil di sini.')
            ->emptyStateIcon('heroicon-o-document');
    }

    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdminSeksi()
            && $ownerRecord->service?->section_id === $user->section_id;
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2, ',', '.') . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2, ',', '.') . ' KB';
        }

        return $bytes . ' B';
    }
}

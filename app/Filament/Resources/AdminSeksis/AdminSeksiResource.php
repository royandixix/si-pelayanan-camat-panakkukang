<?php

namespace App\Filament\Resources\AdminSeksis;

use App\Enums\UserRole;
use App\Filament\Resources\AdminSeksis\Pages\CreateAdminSeksi;
use App\Filament\Resources\AdminSeksis\Pages\EditAdminSeksi;
use App\Filament\Resources\AdminSeksis\Pages\ListAdminSeksis;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdminSeksiResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return 'Kelola Admin';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Pengguna';
    }

    public static function getModelLabel(): string
    {
        return 'Admin Seksi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Admin Seksi';
    }

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'admin-seksi';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Admin')
                    ->description('Akun Admin Seksi dibuat dan dikelola oleh Super Admin.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('nik')
                            ->label('NIK')
                            ->required()
                            ->numeric()
                            ->minLength(16)
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),

                        TextInput::make('email')
                            ->label('Email Administratif')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(30),

                        Select::make('section_id')
                            ->label('Seksi atau Divisi')
                            ->relationship(
                                name: 'section',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (
                                    Builder $query,
                                ): Builder => $query
                                    ->where('is_active', true)
                                    ->orderBy('name'),
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Akun Aktif')
                            ->default(true)
                            ->required(),
                    ]),

                Section::make('Kata Sandi')
                    ->description('Kata sandi awal disampaikan oleh Super Admin kepada Admin Seksi.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->required(
                                fn (
                                    string $operation,
                                ): bool => $operation === 'create',
                            )
                            ->afterStateHydrated(
                                fn (
                                    TextInput $component,
                                ): TextInput => $component->state(null),
                            )
                            ->dehydrated(
                                fn (
                                    ?string $state,
                                ): bool => filled($state),
                            ),

                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Kata Sandi')
                            ->password()
                            ->revealable()
                            ->same('password')
                            ->required(
                                fn (
                                    string $operation,
                                ): bool => $operation === 'create',
                            )
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Admin')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('section.name')
                    ->label('Seksi atau Divisi')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('section_id')
                    ->label('Seksi atau Divisi')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('is_active')
                    ->label('Status Akun')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Edit'),

                Action::make('resetKataSandi')
                    ->label('Reset Kata Sandi')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Kata Sandi Admin')
                    ->schema([
                        TextInput::make('password')
                            ->label('Kata Sandi Baru')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(8),

                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Kata Sandi')
                            ->password()
                            ->revealable()
                            ->required()
                            ->same('password')
                            ->dehydrated(false),
                    ])
                    ->action(function (
                        User $record,
                        array $data,
                    ): void {
                        $record->update([
                            'password' => $data['password'],
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Kata sandi berhasil direset')
                            ->body(
                                'Kata sandi akun '
                                . $record->name
                                . ' telah diperbarui.',
                            )
                            ->send();
                    }),

                DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->defaultSort('name')
            ->emptyStateHeading('Belum ada Admin Seksi')
            ->emptyStateDescription(
                'Buat akun Admin Seksi melalui tombol Tambah Admin.',
            );
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(
                'role',
                UserRole::ADMIN_SEKSI->value,
            );
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdminSeksis::route('/'),
            'create' => CreateAdminSeksi::route('/buat'),
            'edit' => EditAdminSeksi::route('/{record}/ubah'),
        ];
    }
}

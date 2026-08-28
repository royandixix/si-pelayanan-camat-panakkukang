<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('role')
                    ->label('Peran')
                    ->options(UserRole::formOptions())
                    ->default(UserRole::MASYARAKAT->value)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        if (! in_array(
                            $state,
                            UserRole::adminSeksiValues(),
                            true,
                        )) {
                            $set('section_id', null);
                        }
                    })
                    ->required(),

                Select::make('section_id')
                    ->label('Seksi')
                    ->relationship(
                        name: 'section',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('is_active', true)
                            ->orderBy('name'),
                    )
                    ->searchable()
                    ->preload()
                    ->visible(
                        fn ($get): bool =>
                            in_array(
                                $get('role'),
                                UserRole::adminSeksiValues(),
                                true,
                            ),
                    )
                    ->required(
                        fn ($get): bool =>
                            in_array(
                                $get('role'),
                                UserRole::adminSeksiValues(),
                                true,
                            ),
                    )
                    ->dehydrated(
                        fn ($get): bool =>
                            in_array(
                                $get('role'),
                                UserRole::adminSeksiValues(),
                                true,
                            ),
                    ),

                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                TextInput::make('nik')
                    ->label('NIK')
                    ->inputMode('numeric')
                    ->rules(['regex:/^[0-9]{16}$/'])
                    ->length(16)
                    ->unique(ignoreRecord: true),

                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->maxLength(20),

                Textarea::make('address')
                    ->label('Alamat')
                    ->rows(4)
                    ->placeholder('Masukkan alamat lengkap')
                    ->columnSpanFull(),

                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->maxLength(255)
                    ->required(
                        fn (string $operation): bool =>
                            $operation === 'create',
                    )
                    ->dehydrated(
                        fn (?string $state): bool =>
                            filled($state),
                    )
                    ->helperText('Kosongkan saat mengubah data jika kata sandi tidak ingin diganti.'),

                DateTimePicker::make('email_verified_at')
                    ->label('Email Diverifikasi Pada')
                    ->native(false)
                    ->displayFormat('d M Y H:i')
                    ->seconds(false),

                Toggle::make('is_active')
                    ->label('Akun Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}
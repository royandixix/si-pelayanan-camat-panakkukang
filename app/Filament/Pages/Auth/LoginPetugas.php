<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserRole;
use Filament\Auth\Pages\Login;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

class LoginPetugas extends Login
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('role')
                    ->label('Pilih Role')
                    ->placeholder('Pilih role untuk masuk')
                    ->options([
                        UserRole::SUPER_ADMIN->value => 'Super Admin',
                        UserRole::ADMIN_SEKSI->value => 'Admin Seksi',
                        UserRole::PIMPINAN->value => 'Camat',
                    ])
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('login', null);
                    })
                    ->required()
                    ->validationMessages([
                        'required' => 'Role wajib dipilih.',
                    ]),

                TextInput::make('login')
                    ->label(
                        fn (Get $get): string => match (
                            $get('role')
                        ) {
                            UserRole::SUPER_ADMIN->value => 'NIK Super Admin',
                            UserRole::ADMIN_SEKSI->value => 'NIK Admin Seksi',
                            UserRole::PIMPINAN->value => 'Email Camat',
                            default => 'NIK atau Email',
                        },
                    )
                    ->placeholder(
                        fn (Get $get): string => match (
                            $get('role')
                        ) {
                            UserRole::SUPER_ADMIN->value => 'Masukkan NIK Super Admin',
                            UserRole::ADMIN_SEKSI->value => 'Masukkan NIK Admin Seksi',
                            UserRole::PIMPINAN->value => 'Masukkan email Camat',
                            default => 'Pilih role terlebih dahulu',
                        },
                    )
                    ->helperText(
                        fn (Get $get): string => match (
                            $get('role')
                        ) {
                            UserRole::SUPER_ADMIN->value => 'Super Admin masuk menggunakan NIK yang terdaftar.',
                            UserRole::ADMIN_SEKSI->value => 'Admin Seksi masuk menggunakan NIK dan kata sandi yang dibuat oleh Super Admin.',
                            UserRole::PIMPINAN->value => 'Camat masuk menggunakan alamat email yang terdaftar.',
                            default => 'Pilih role agar sistem menentukan apakah Anda harus menggunakan NIK atau email.',
                        },
                    )
                    ->disabled(
                        fn (Get $get): bool => blank(
                            $get('role'),
                        ),
                    )
                    ->required()
                    ->maxLength(255)
                    ->autocomplete('username')
                    ->autofocus()
                    ->validationMessages([
                        'required' => 'NIK atau email wajib diisi.',
                    ]),

                $this->getPasswordFormComponent(),

                $this->getRememberFormComponent(),
            ]);
    }

    protected function getCredentialsFromFormData(
        #[SensitiveParameter] array $data,
    ): array {
        $role = UserRole::tryFrom(
            (string) ($data['role'] ?? ''),
        );

        if (
            ! $role
            || ! in_array(
                $role,
                [
                    UserRole::SUPER_ADMIN,
                    UserRole::ADMIN_SEKSI,
                    UserRole::PIMPINAN,
                ],
                true,
            )
        ) {
            throw ValidationException::withMessages([
                'data.role' => 'Silakan pilih role yang valid.',
            ]);
        }

        $kolomLogin = match ($role) {
            UserRole::SUPER_ADMIN,
            UserRole::ADMIN_SEKSI => 'nik',
            UserRole::PIMPINAN => 'email',
            default => throw ValidationException::withMessages([
                'data.role' => 'Role tidak memiliki akses login petugas.',
            ]),
        };

        $nilaiLogin = trim(
            (string) ($data['login'] ?? ''),
        );

        if ($kolomLogin === 'email') {
            $nilaiLogin = mb_strtolower(
                $nilaiLogin,
            );
        }

        return [
            $kolomLogin => $nilaiLogin,
            'password' => $data['password'],
            'role' => $role->value,
            'is_active' => true,
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => 'Identitas login, kata sandi, atau role tidak sesuai.',
        ]);
    }

    public function getTitle(): string|Htmlable
    {
        return 'Login Petugas';
    }

    public function getHeading(): string|Htmlable|null
    {
        return 'Masuk ke akun Anda';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Pilih role, lalu masukkan identitas akun yang sesuai.';
    }
}

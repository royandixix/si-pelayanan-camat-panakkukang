<?php

namespace App\Http\Responses;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;

class LoginPetugasResponse implements LoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        $pengguna = Filament::auth()->user();

        if (! $pengguna instanceof User) {
            return new RedirectResponse(
                route('filament.petugas.auth.login'),
            );
        }

        $role = $pengguna->role instanceof UserRole
            ? $pengguna->role
            : UserRole::tryFrom(
                (string) $pengguna->role,
            );

        $urlTujuan = match ($role) {
            UserRole::SUPER_ADMIN,
            UserRole::ADMIN_SEKSI => route(
                'filament.admin.pages.dashboard',
            ),
            UserRole::PIMPINAN => route(
                'filament.pimpinan.pages.dashboard',
            ),
            default => route(
                'filament.petugas.auth.login',
            ),
        };

        return new RedirectResponse(
            $urlTujuan,
        );
    }
}

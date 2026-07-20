<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as BaseAuthenticate;

class AuthenticateFilament extends BaseAuthenticate
{
    protected function redirectTo($request): ?string
    {
        return route('filament.petugas.auth.login');
    }
}

<?php

namespace App\Providers;

use App\Http\Responses\LoginPetugasResponse;
use App\Http\Responses\LogoutPetugasResponse;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse;
use Illuminate\Support\ServiceProvider;

class FilamentAuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            LoginResponse::class,
            LoginPetugasResponse::class,
        );

        $this->app->singleton(
            LogoutResponse::class,
            LogoutPetugasResponse::class,
        );
    }
}

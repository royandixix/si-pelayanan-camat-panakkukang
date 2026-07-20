<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class PimpinanSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email'=>'pimpinan@panakkukang.go.id'],
            [
                'name'=>'Camat Panakkukang',
                'password'=>'password123',
                'role'=>UserRole::PIMPINAN,
                'nik'=>null,
                'phone'=>null,
                'address'=>null,
                'section_id'=>null,
                'email_verified_at'=>now(),
                'is_active'=>true,
            ],
        );
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SectionSeeder::class,
            ServiceSeeder::class,
            PelayananKtpSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
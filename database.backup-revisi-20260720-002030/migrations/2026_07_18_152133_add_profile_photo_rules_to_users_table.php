<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tambahEkstensi = ! Schema::hasColumn(
            'users',
            'profile_photo_allowed_extensions',
        );

        $tambahMime = ! Schema::hasColumn(
            'users',
            'profile_photo_allowed_mime_types',
        );

        $tambahUkuran = ! Schema::hasColumn(
            'users',
            'profile_photo_max_size_kb',
        );

        if (! $tambahEkstensi && ! $tambahMime && ! $tambahUkuran) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use (
            $tambahEkstensi,
            $tambahMime,
            $tambahUkuran,
        ): void {
            if ($tambahEkstensi) {
                $table
                    ->string('profile_photo_allowed_extensions', 100)
                    ->default('jpg,jpeg,png,webp');
            }

            if ($tambahMime) {
                $table
                    ->string('profile_photo_allowed_mime_types', 150)
                    ->default('image/jpeg,image/png,image/webp');
            }

            if ($tambahUkuran) {
                $table
                    ->unsignedInteger('profile_photo_max_size_kb')
                    ->default(3072);
            }
        });
    }

    public function down(): void
    {
        $kolom = [];

        if (Schema::hasColumn('users', 'profile_photo_allowed_extensions')) {
            $kolom[] = 'profile_photo_allowed_extensions';
        }

        if (Schema::hasColumn('users', 'profile_photo_allowed_mime_types')) {
            $kolom[] = 'profile_photo_allowed_mime_types';
        }

        if (Schema::hasColumn('users', 'profile_photo_max_size_kb')) {
            $kolom[] = 'profile_photo_max_size_kb';
        }

        if ($kolom === []) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($kolom): void {
            $table->dropColumn($kolom);
        });
    }
};
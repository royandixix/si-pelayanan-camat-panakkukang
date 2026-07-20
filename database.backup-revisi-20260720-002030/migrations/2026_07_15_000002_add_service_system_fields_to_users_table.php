<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('section_id')
                ->nullable()
                ->after('id')
                ->constrained('sections')
                ->nullOnDelete();

            $table->string('nik', 20)->nullable()->unique()->after('name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('role', 30)->default('masyarakat')->index()->after('password');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('section_id');
            $table->dropUnique(['nik']);
            $table->dropColumn([
                'nik',
                'phone',
                'address',
                'role',
                'is_active',
            ]);
        });
    }
};

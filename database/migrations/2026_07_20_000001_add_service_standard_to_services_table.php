<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (
            Blueprint $table,
        ): void {
            $table
                ->json('service_standard')
                ->nullable()
                ->after('form_schema');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (
            Blueprint $table,
        ): void {
            $table->dropColumn('service_standard');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            'ALTER TABLE service_queues MODIFY application_id BIGINT UNSIGNED NULL'
        );
    }

    public function down(): void
    {
        DB::table('service_queues')
            ->whereNull('application_id')
            ->delete();

        DB::statement(
            'ALTER TABLE service_queues MODIFY application_id BIGINT UNSIGNED NOT NULL'
        );
    }
};

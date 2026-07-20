<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_queues', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('application_id')
                ->unique()
                ->constrained('service_applications')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('section_id')
                ->constrained('sections')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('queue_date');
            $table->string('prefix', 5)->default('A');
            $table->unsignedSmallInteger('sequence');
            $table->string('queue_number', 20);
            $table->string('status', 20)->default('waiting')->index();
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('service_started_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['section_id', 'queue_date', 'sequence'],
                'service_queues_daily_sequence_unique'
            );

            $table->index(['queue_date', 'status']);
            $table->index(['section_id', 'queue_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_queues');
    }
};

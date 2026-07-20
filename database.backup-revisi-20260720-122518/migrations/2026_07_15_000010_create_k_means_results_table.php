<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('k_means_results', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('k_means_run_id')
                ->constrained('k_means_runs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('section_id')
                ->constrained('sections')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedInteger('service_volume')->default(0);
            $table->unsignedInteger('queue_volume')->default(0);
            $table->unsignedInteger('total_volume')->default(0);
            $table->unsignedSmallInteger('employee_count')->default(0);
            $table->unsignedTinyInteger('cluster_number');
            $table->decimal('centroid', 18, 6);
            $table->decimal('distance_to_centroid', 18, 6)->default(0);
            $table->string('workload_category', 20);
            $table->unsignedTinyInteger('rank')->nullable();
            $table->smallInteger('recommended_employee_change')->default(0);
            $table->text('recommendation')->nullable();
            $table->timestamps();

            $table->unique(
                ['k_means_run_id', 'section_id'],
                'k_means_results_run_section_unique'
            );

            $table->index(['cluster_number', 'rank']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k_means_results');
    }
};

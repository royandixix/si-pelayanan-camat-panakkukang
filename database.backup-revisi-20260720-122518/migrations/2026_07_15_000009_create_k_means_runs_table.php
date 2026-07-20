<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('k_means_runs', function (Blueprint $table): void {
            $table->id();
            $table->date('period_start');
            $table->date('period_end');
            $table->unsignedTinyInteger('cluster_count');
            $table->string('status', 20)->default('pending')->index();
            $table->unsignedInteger('iterations')->nullable();
            $table->decimal('wcss', 18, 6)->nullable();
            $table->decimal('silhouette_score', 10, 6)->nullable();
            $table->decimal('davies_bouldin_index', 10, 6)->nullable();
            $table->json('input_snapshot')->nullable();

            $table->foreignId('executed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('executed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['period_start', 'period_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k_means_runs');
    }
};

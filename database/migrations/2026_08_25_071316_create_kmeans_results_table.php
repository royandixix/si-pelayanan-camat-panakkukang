<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kmeans_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kmeans_run_id')
                ->constrained('kmeans_runs')
                ->cascadeOnDelete();
            $table->string('dataset_name');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedInteger('jumlah_pelayanan');
            $table->unsignedInteger('hari_aktif');
            $table->decimal('rata_rata_harian', 10, 4);
            $table->decimal('z_jumlah_pelayanan', 12, 8);
            $table->decimal('z_hari_aktif', 12, 8);
            $table->unsignedTinyInteger('cluster');
            $table->string('cluster_label');
            $table->decimal('distance_to_centroid', 14, 8);
            $table->string('reference_label')->nullable();
            $table->timestamps();

            $table->unique(
                ['kmeans_run_id', 'dataset_name', 'year', 'month'],
                'kmeans_result_period_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kmeans_results');
    }
};

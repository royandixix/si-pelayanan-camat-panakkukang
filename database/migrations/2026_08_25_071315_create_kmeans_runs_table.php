<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kmeans_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('k')->default(3);
            $table->unsignedInteger('total_source_records');
            $table->unsignedInteger('valid_source_records');
            $table->unsignedInteger('excluded_records');
            $table->unsignedInteger('total_points');
            $table->json('features');
            $table->string('normalization')->default('z_score');
            $table->unsignedInteger('iterations')->default(0);
            $table->decimal('wcss', 18, 8)->nullable();
            $table->decimal('silhouette_score', 8, 6)->nullable();
            $table->json('cluster_centroids')->nullable();
            $table->string('status')->default('completed');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kmeans_runs');
    }
};

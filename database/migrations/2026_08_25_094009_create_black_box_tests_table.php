<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('black_box_tests', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('module');
            $table->text('scenario');
            $table->text('test_input')->nullable();
            $table->text('expected_result');
            $table->text('actual_result')->nullable();
            $table->string('status')->default('belum_diuji');
            $table->timestamp('tested_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('black_box_tests');
    }
};

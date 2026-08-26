<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_dataset_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('dataset_name');
            $table->string('source_file');
            $table->unsignedInteger('source_row_no')->nullable();
            $table->date('record_date')->nullable();
            $table->string('raw_date')->nullable();
            $table->string('subject_name')->nullable();
            $table->text('description')->nullable();
            $table->string('validation_status')->default('valid');
            $table->timestamps();

            $table->unique([
                'source_file',
                'source_row_no',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_dataset_records');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_applications', function (Blueprint $table): void {
            $table->id();
            $table->string('registration_number', 40)->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('assigned_admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('status', 30)->default('draft')->index();
            $table->json('applicant_data')->nullable();
            $table->text('applicant_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['service_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['assigned_admin_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_applications');
    }
};

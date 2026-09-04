<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('problem_reports', function (Blueprint $table) {
            $table->id();

            $table->string('reference_no')->unique();

            $table->foreignId('reported_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('location');
            $table->text('problem_description');
            $table->string('photo_path')->nullable();

            $table->string('status')->default('reported');
            // reported -> reviewed -> converted (a Job Request was created
            //          -> from this report)
            //          -> resolved (fixed without needing a Job Request,
            //             e.g. already handled directly)
            //          -> dismissed (not a real issue / duplicate)

            // Physical Plant and Services' assessment — is there a budget
            // or item/material need before this can become a real Job
            // Request, or can it just be fixed outright.
            $table->text('admin_notes')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            // Set once Physical Plant and Services converts this report
            // into an actual Job Request to get it fixed/assigned.
            $table->foreignId('job_request_id')
                ->nullable()
                ->constrained('job_requests')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problem_reports');
    }
};

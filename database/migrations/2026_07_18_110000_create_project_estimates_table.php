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
        Schema::create('project_estimates', function (Blueprint $table) {
            $table->id();

            $table->string('reference_no')->unique();

            $table->string('project_name');
            $table->string('location')->nullable();

            $table->foreignId('prepared_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('scope_of_work')->nullable();
            $table->string('duration')->nullable();
            $table->text('assumptions')->nullable();
            $table->text('exclusions')->nullable();

            // Optional traceability — a repair project's estimate often
            // originates from an approved Job Request, but this is a
            // convenience link, not a required workflow step.
            $table->foreignId('job_request_id')
                ->nullable()
                ->constrained('job_requests')
                ->nullOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_estimates');
    }
};

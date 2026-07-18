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
        Schema::create('utility_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('personnel_id')
                ->constrained('personnel')
                ->cascadeOnDelete();

            $table->date('schedule_date');

            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();

            $table->string('task');
            $table->string('location')->nullable();
            $table->text('notes')->nullable();

            // Optional traceability link when a scheduled slot is for a
            // specific Job Request — not the primary mechanism, Mark builds
            // the roster directly (see UtilityScheduleController).
            $table->foreignId('job_request_id')
                ->nullable()
                ->constrained('job_requests')
                ->nullOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_schedules');
    }
};

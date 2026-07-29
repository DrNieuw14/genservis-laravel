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
        Schema::create('class_schedules', function (Blueprint $table) {

            $table->id();

            $table->foreignId('section_id')
                ->constrained('sections')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            // Nullable — the real schedule genuinely has "TBA" faculty for
            // some slots (not yet assigned), same convention as room below.
            $table->foreignId('personnel_id')
                ->nullable()
                ->constrained('personnel')
                ->nullOnDelete();

            // Plain string, not a Room FK — real room codes are informal and
            // inconsistent (S-303, 401, ALAN 2, TBA); forcing a rigid room
            // catalog here would fight the source data rather than reflect it.
            $table->string('room')->nullable();

            $table->enum('day_of_week', [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
            ]);

            $table->time('start_time');

            $table->time('end_time');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['section_id', 'day_of_week']);
            $table->index(['personnel_id', 'day_of_week']);
            $table->index(['room', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};

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
        Schema::create('faculty_profiles', function (Blueprint $table) {

            $table->id();

            // 1:1 extension of an existing Personnel record — faculty-only
            // fields live here rather than cluttering the base Personnel
            // table with columns that only apply to a subset of employees.
            $table->foreignId('personnel_id')
                ->unique()
                ->constrained('personnel')
                ->cascadeOnDelete();

            $table->string('highest_educational_attainment')->nullable();

            $table->text('consultation_schedule')->nullable();

            $table->string('designation')->nullable();

            $table->text('research')->nullable();

            $table->text('extension')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_profiles');
    }
};

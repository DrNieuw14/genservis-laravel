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
        Schema::create('sections', function (Blueprint $table) {

            $table->id();

            $table->foreignId('program_id')
                ->constrained('programs')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('year_level');

            // e.g. "A", "B", "1C" style suffix — combined with year_level and
            // program code to form the display label "BSIT 1A".
            $table->string('section_letter', 5);

            // e.g. "2026-2027"
            $table->string('school_year', 20);

            $table->enum('semester', ['1st Semester', '2nd Semester', 'Summer']);

            $table->unsignedInteger('number_of_students')->nullable();

            $table->timestamps();

            $table->unique(['program_id', 'year_level', 'section_letter', 'school_year', 'semester'], 'sections_unique_combo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_educations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('personnel_id')
                ->constrained('personnel')
                ->cascadeOnDelete();

            $table->enum('education_level', [
                'Elementary',
                'Secondary',
                'Vocational',
                'College',
                'Graduate Studies'
            ]);

            $table->string('school_name');

            $table->string('degree_course')->nullable();

            $table->string('highest_level')->nullable();

            $table->year('year_graduated')->nullable();

            $table->year('from_year')->nullable();

            $table->year('to_year')->nullable();

            $table->string('honors')->nullable();

            $table->string('units_earned')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_educations');
    }
};

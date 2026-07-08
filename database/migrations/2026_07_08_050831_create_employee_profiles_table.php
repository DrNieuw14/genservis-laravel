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
        Schema::create('employee_profiles', function (Blueprint $table) {

            $table->id();

            $table->foreignId('personnel_id')
                ->constrained('personnel')
                ->cascadeOnDelete();

            // Personal Information
            $table->date('birthdate')->nullable();

            $table->enum('gender', [
                'Male',
                'Female'
            ])->nullable();

            $table->string('civil_status')->nullable();

            $table->string('nationality')->nullable();

            $table->string('religion')->nullable();

            $table->string('blood_type')->nullable();

            $table->string('photo')->nullable();

            $table->timestamps();

            $table->unique('personnel_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
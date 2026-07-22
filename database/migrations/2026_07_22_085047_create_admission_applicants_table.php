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
        Schema::create('admission_applicants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admission_year_id')->constrained()->cascadeOnDelete();

            // Unique per year, not globally — the source sheet's own Control
            // Number resets/differs per admission batch, so scoping the
            // uniqueness lets next year's roster reuse the same numbers.
            $table->string('control_number');

            $table->string('given_name');
            $table->string('middle_name')->nullable();
            $table->string('family_name');
            $table->string('suffix')->nullable();
            $table->date('date_of_birth');
            $table->string('sex')->nullable();

            $table->string('house_no')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality')->nullable();
            $table->string('municipality_income_class')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code')->nullable();

            $table->string('campus')->nullable();
            $table->string('program')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();

            $table->timestamps();

            $table->unique(['admission_year_id', 'control_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_applicants');
    }
};

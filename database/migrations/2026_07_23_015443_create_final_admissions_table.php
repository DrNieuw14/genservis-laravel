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
        Schema::create('final_admissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admission_year_id')->constrained()->cascadeOnDelete();

            $table->string('program_code');
            $table->string('program_name')->nullable();

            $table->foreignId('admission_applicant_id')->constrained()->cascadeOnDelete();

            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('notes')->nullable();

            $table->timestamps();

            // One final placement per applicant per admission year — a
            // student can't be finally admitted into two programs at once.
            // Moving them means removing the old entry first, not having
            // both exist simultaneously.
            $table->unique(['admission_year_id', 'admission_applicant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_admissions');
    }
};

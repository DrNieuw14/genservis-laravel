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
        Schema::create('program_quotas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admission_year_id')->constrained()->cascadeOnDelete();

            // Keyed by the short code (BSIT, BSHM, etc.) — the same key
            // already used for grouping/URLs on the rankings pages, not
            // the full official program name.
            $table->string('program_code');
            $table->string('program_name')->nullable();

            $table->unsignedInteger('sections')->default(0);
            $table->unsignedInteger('students_per_section')->default(0);

            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->unique(['admission_year_id', 'program_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_quotas');
    }
};

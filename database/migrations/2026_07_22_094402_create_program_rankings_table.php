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
        Schema::create('program_rankings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admission_year_id')->constrained()->cascadeOnDelete();

            $table->string('code')->nullable();
            $table->string('examinee_name');
            $table->string('program')->nullable();
            $table->string('address')->nullable();

            $table->unsignedInteger('raw_score')->nullable();
            $table->decimal('grade', 6, 2)->nullable();
            $table->string('percentile_rank')->nullable();

            // Left nullable/unused until a passing cutoff or per-program
            // quota is decided — the source "Master List" has no PASSED/
            // FAILED marker at all, only a rank order per program, so this
            // is a placeholder for whenever that decision is made, not a
            // computed value.
            $table->string('remarks')->nullable();

            $table->foreignId('admission_applicant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('match_status')->default('not_found');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_rankings');
    }
};

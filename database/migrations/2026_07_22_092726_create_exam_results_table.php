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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_session_id')->constrained()->cascadeOnDelete();

            // Nullable + a snapshotted name — the roster cross-check match
            // can fail (code not found, or found but the name doesn't
            // agree), and the raw result row must still be visible either
            // way for staff to review, not silently dropped.
            $table->foreignId('admission_applicant_id')->nullable()->constrained()->nullOnDelete();

            $table->string('code')->nullable();
            $table->string('examinee_name');

            $table->unsignedInteger('raw_score')->nullable();
            $table->decimal('grade', 6, 2)->nullable();
            $table->string('percentile_rank')->nullable();
            $table->string('remarks')->nullable();

            // 'matched' (code found in roster, name agrees), 'name_mismatch'
            // (code found but the roster name doesn't match — a real data
            // problem worth a human look), 'not_found' (code missing from
            // the roster entirely, or blank on the results sheet).
            $table->string('match_status')->default('not_found');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};

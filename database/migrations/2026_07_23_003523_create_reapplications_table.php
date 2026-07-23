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
        Schema::create('reapplications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admission_year_id')->constrained()->cascadeOnDelete();

            $table->string('email')->nullable();
            $table->string('surname');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();

            // Freeform, not the fixed roster campus list — this form is
            // open to every CvSU campus, not just Carmona.
            $table->string('address')->nullable();
            $table->string('campus')->nullable();

            // Freeform, not restricted to the known 10 programs — the
            // source form has a real "Others" option, kept verbatim
            // rather than force-mapped to a wrong bucket.
            $table->string('program_applied_for')->nullable();

            $table->string('control_number')->nullable();
            $table->string('track')->nullable();

            // Merged from the source form's two mutually-exclusive
            // branches (ACADEMIC track's strand dropdown vs TVL track's) —
            // whichever one the respondent actually saw and filled in.
            $table->string('strand')->nullable();

            $table->string('first_choice')->nullable();
            $table->string('second_choice')->nullable();

            $table->foreignId('admission_applicant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('match_status')->default('not_found');

            // A second, real submission sharing the same valid Control
            // Number as another row in this same upload — flagged for
            // manual review rather than silently merged, since the source
            // sheet has no timestamp to say which submission is final.
            $table->boolean('is_duplicate')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reapplications');
    }
};

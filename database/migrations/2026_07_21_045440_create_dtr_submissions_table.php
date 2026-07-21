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
        Schema::create('dtr_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('personnel_id')
                ->constrained('personnel')
                ->cascadeOnDelete();

            // 'YYYY-MM' — one submission per person per calendar month.
            $table->string('month', 7);

            // draft (default/reset-after-rejection) -> employee_verified ->
            // mark_checked -> hr_approved.
            $table->enum('status', ['draft', 'employee_verified', 'mark_checked', 'hr_approved'])
                ->default('draft');

            $table->timestamp('employee_verified_at')->nullable();

            $table->timestamp('mark_checked_at')->nullable();
            $table->foreignId('mark_checked_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('hr_approved_at')->nullable();
            $table->foreignId('hr_approved_by')->nullable()->constrained('users')->nullOnDelete();

            // A reject at either the Mark or HR stage resets status back to
            // 'draft' and records why, so the employee knows what to fix
            // before re-verifying.
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('rejected_stage')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            $table->unique(['personnel_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dtr_submissions');
    }
};

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
        Schema::create('energy_conservation_reports', function (Blueprint $table) {
            $table->id();

            // 'YYYY-MM' — one report per calendar month, matching the
            // template's "Reporting Month" field.
            $table->string('report_month', 7)->unique();

            $table->string('campus')->default('CvSU Carmona Campus');

            // Energy Consumption Monitoring table — Electricity Bill (₱)
            // and Consumption (kWh), previous vs current month. Difference
            // and % change are always computed live, never stored, matching
            // this app's established "trust the computation" convention.
            $table->decimal('previous_month_bill', 12, 2)->nullable();
            $table->decimal('current_month_bill', 12, 2)->nullable();
            $table->decimal('previous_month_consumption', 12, 2)->nullable();
            $table->decimal('current_month_consumption', 12, 2)->nullable();
            $table->text('remarks_analysis')->nullable();

            // Energy Conservation Measures Implemented — fixed checklist
            // (7 items) from the template, stored as an array of the
            // selected keys.
            $table->json('measures_implemented')->nullable();
            $table->string('other_measures')->nullable();

            $table->text('summary_of_accomplishments')->nullable();

            // Simple 2-state status. The template's own "reviewed by Campus
            // Administrator before submission" step is recorded as a plain
            // name/date rather than a second system account clicking
            // approve — there's no Campus Administrator account/role in
            // this system, only a printed title.
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->string('reviewed_by_name')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('energy_conservation_reports');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_bills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('water_meter_id')
                ->constrained()
                ->cascadeOnDelete();

            // "For the month of April 2026" on the real Billing Notice —
            // distinct from period_start/period_end, which cover the
            // actual meter-reading cycle (e.g. 03/18-04/21).
            $table->string('report_month');

            $table->string('bill_number')->nullable();
            $table->string('seq_no')->nullable();
            $table->date('billing_date')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();

            $table->decimal('previous_reading', 10, 2)->nullable();
            $table->decimal('present_reading', 10, 2)->nullable();

            $table->decimal('water_bill', 12, 2)->nullable();
            $table->decimal('esf', 12, 2)->nullable();

            // Stored directly rather than computed — the real penalty
            // added after the due date isn't a clean fixed percentage of
            // water_bill+esf on the sample bills, so trust what's printed.
            $table->decimal('amount_after_due_date', 12, 2)->nullable();

            $table->date('due_date')->nullable();
            $table->date('disconnection_date')->nullable();
            $table->string('meter_reader_name')->nullable();

            $table->text('remarks')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // One bill per meter per reading period — guards against
            // accidentally double-entering the same physical Billing Notice.
            $table->unique(['water_meter_id', 'period_start', 'period_end'], 'water_bills_meter_period_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_bills');
    }
};

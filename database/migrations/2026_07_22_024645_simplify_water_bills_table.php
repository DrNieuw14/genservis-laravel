<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('water_bills', function (Blueprint $table) {
            // The composite unique index is the only index currently
            // backing the water_meter_id foreign key — drop the FK first
            // so MySQL doesn't refuse to drop that index.
            $table->dropForeign(['water_meter_id']);
            $table->dropUnique('water_bills_meter_period_unique');
        });

        Schema::table('water_bills', function (Blueprint $table) {
            $table->dropColumn([
                'bill_number',
                'seq_no',
                'billing_date',
                'period_start',
                'period_end',
                'disconnection_date',
            ]);

            // report_month is now the sole "when is this bill for" field —
            // one bill per meter per month.
            $table->unique(['water_meter_id', 'report_month'], 'water_bills_meter_month_unique');
            $table->foreign('water_meter_id')->references('id')->on('water_meters')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('water_bills', function (Blueprint $table) {
            $table->dropForeign(['water_meter_id']);
            $table->dropUnique('water_bills_meter_month_unique');
        });

        Schema::table('water_bills', function (Blueprint $table) {
            $table->string('bill_number')->nullable();
            $table->string('seq_no')->nullable();
            $table->date('billing_date')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->date('disconnection_date')->nullable();

            $table->unique(['water_meter_id', 'period_start', 'period_end'], 'water_bills_meter_period_unique');
            $table->foreign('water_meter_id')->references('id')->on('water_meters')->cascadeOnDelete();
        });
    }
};

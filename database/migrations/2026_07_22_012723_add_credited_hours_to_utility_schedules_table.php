<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('utility_schedules', function (Blueprint $table) {
            // Manual override for the DTR's "Hours Worked" total — null
            // means the auto-computed value applies (8 hrs standard day
            // minus undertime, 0 for a no-show, 8 for approved leave).
            // GSO/HR can correct a day (e.g. a forgot-to-clock-out entry
            // that raw-computes to an implausible 12+ hrs) by setting this.
            $table->decimal('credited_hours', 5, 2)->nullable()->after('overtime_reason');
        });
    }

    public function down(): void
    {
        Schema::table('utility_schedules', function (Blueprint $table) {
            $table->dropColumn('credited_hours');
        });
    }
};

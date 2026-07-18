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
        Schema::table('utility_schedules', function (Blueprint $table) {

            $table->timestamp('time_in')->nullable()->after('job_request_id');
            $table->timestamp('time_out')->nullable()->after('time_in');

            // Minutes past shift_end (beyond the grace period) — computed
            // and stored at checkout time so reports can SUM() without
            // recalculating every entry. Null until checked out.
            $table->unsignedInteger('overtime_minutes')->nullable()->after('time_out');

            $table->string('overtime_reason')->nullable()->after('overtime_minutes');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utility_schedules', function (Blueprint $table) {
            $table->dropColumn(['time_in', 'time_out', 'overtime_minutes', 'overtime_reason']);
        });
    }
};

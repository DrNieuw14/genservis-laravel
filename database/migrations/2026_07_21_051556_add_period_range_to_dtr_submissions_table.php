<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Submissions used to be one-per-calendar-month; the pipeline now
        // supports any custom range (e.g. the standard 1st-15th / 16th-end
        // semi-monthly government payroll cutoffs), so the period itself —
        // not just its containing month — is what identifies a submission.
        if (!Schema::hasColumn('dtr_submissions', 'period_start')) {
            Schema::table('dtr_submissions', function (Blueprint $table) {
                $table->date('period_start')->nullable()->after('month');
                $table->date('period_end')->nullable()->after('period_start');
            });
        }

        foreach (DB::table('dtr_submissions')->whereNull('period_start')->get() as $row) {
            $start = \Illuminate\Support\Carbon::parse($row->month . '-01')->startOfMonth();
            DB::table('dtr_submissions')->where('id', $row->id)->update([
                'period_start' => $start->toDateString(),
                'period_end' => $start->copy()->endOfMonth()->toDateString(),
            ]);
        }

        // Add the new unique index BEFORE dropping the old one — MySQL/
        // InnoDB refuses to drop personnel_id_month_unique while it's the
        // only index covering the personnel_id foreign key, so the
        // replacement (which also covers personnel_id as its leftmost
        // column) needs to exist first.
        $indexes = collect(DB::select('SHOW INDEX FROM dtr_submissions'))->pluck('Key_name')->unique();

        if (!$indexes->contains('dtr_submissions_personnel_id_period_start_period_end_unique')) {
            Schema::table('dtr_submissions', function (Blueprint $table) {
                $table->unique(['personnel_id', 'period_start', 'period_end']);
            });
        }

        if ($indexes->contains('dtr_submissions_personnel_id_month_unique')) {
            Schema::table('dtr_submissions', function (Blueprint $table) {
                $table->dropUnique(['personnel_id', 'month']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dtr_submissions', function (Blueprint $table) {
            $table->unique(['personnel_id', 'month']);
            $table->dropUnique(['personnel_id', 'period_start', 'period_end']);
            $table->dropColumn(['period_start', 'period_end']);
        });
    }
};

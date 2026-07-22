<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_request_photos', function (Blueprint $table) {
            // 'work_done' is the default so every existing row (all of
            // which were uploaded via the work-done flow, the only one
            // that existed before) keeps its correct meaning without a
            // backfill step.
            $table->enum('type', ['request_evidence', 'work_done'])
                ->default('work_done')
                ->after('job_request_id');
        });
    }

    public function down(): void
    {
        Schema::table('job_request_photos', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

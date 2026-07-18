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
        Schema::table('job_requests', function (Blueprint $table) {

            // Set when an assigned worker (e.g. Rony) flags their part of
            // the job as finished — distinct from the approver's own
            // "Mark Completed" close-out, which happens after this.
            $table->foreignId('work_done_by')
                ->nullable()
                ->after('assigned_at')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('work_done_at')
                ->nullable()
                ->after('work_done_by');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_requests', function (Blueprint $table) {
            $table->dropForeign(['work_done_by']);
            $table->dropColumn(['work_done_by', 'work_done_at']);
        });
    }
};

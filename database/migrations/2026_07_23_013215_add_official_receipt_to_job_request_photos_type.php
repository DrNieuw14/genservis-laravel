<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE job_request_photos MODIFY COLUMN type ENUM('request_evidence', 'work_done', 'official_receipt') NOT NULL DEFAULT 'work_done'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE job_request_photos MODIFY COLUMN type ENUM('request_evidence', 'work_done') NOT NULL DEFAULT 'work_done'");
    }
};

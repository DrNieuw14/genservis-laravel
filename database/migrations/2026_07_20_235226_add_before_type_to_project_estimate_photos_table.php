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
        // before = current status of the project prior to work starting,
        // required to exist before a work_done photo can be uploaded
        // (enforced in ProjectEstimateController::uploadPhotos()).
        DB::statement("ALTER TABLE project_estimate_photos MODIFY COLUMN type ENUM('before', 'receipt', 'work_done', 'other') NOT NULL DEFAULT 'other'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE project_estimate_photos SET type = 'other' WHERE type = 'before'");
        DB::statement("ALTER TABLE project_estimate_photos MODIFY COLUMN type ENUM('receipt', 'work_done', 'other') NOT NULL DEFAULT 'other'");
    }
};

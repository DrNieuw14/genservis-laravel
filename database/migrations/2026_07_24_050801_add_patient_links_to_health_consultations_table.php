<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_consultations', function (Blueprint $table) {

            // Optional traceability back to the source record the nurse
            // quick-filled from — the patient_* columns stay the source of
            // truth for display/print either way, same "bonus link, not the
            // backbone" pattern as job_request_id on UtilitySchedule.
            $table->foreignId('admission_applicant_id')
                ->nullable()
                ->after('created_by')
                ->constrained('admission_applicants')
                ->nullOnDelete();

            $table->foreignId('personnel_id')
                ->nullable()
                ->after('admission_applicant_id')
                ->constrained('personnel')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('health_consultations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('admission_applicant_id');
            $table->dropConstrainedForeignId('personnel_id');
        });
    }
};

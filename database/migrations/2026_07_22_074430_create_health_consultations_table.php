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
        Schema::create('health_consultations', function (Blueprint $table) {
            $table->id();

            $table->string('case_no')->unique();
            $table->date('consultation_date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();

            // Patient info — freeform, matches the paper form exactly. Not
            // linked to the Personnel table since a campus clinic sees
            // students and visitors too, not just employees.
            $table->string('patient_name');
            $table->unsignedSmallInteger('patient_age')->nullable();
            $table->string('patient_sex')->nullable();
            $table->string('patient_civil_status')->nullable();
            $table->string('patient_address')->nullable();
            $table->date('patient_birthday')->nullable();

            $table->text('chief_complaint')->nullable();

            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_no')->nullable();

            $table->date('previous_consultation_date')->nullable();
            $table->string('previous_diagnosis')->nullable();
            $table->string('previous_medications')->nullable();
            $table->string('previous_attending_physician')->nullable();

            // Assessment section
            $table->string('mode_of_arrival')->nullable();
            $table->boolean('has_injuries')->default(false);
            $table->json('injury_types')->nullable();
            $table->string('injury_other_text')->nullable();
            $table->string('noi')->nullable();
            $table->string('poi')->nullable();
            $table->date('doi')->nullable();
            $table->time('toi')->nullable();

            $table->string('vital_bp')->nullable();
            $table->string('vital_temp')->nullable();
            $table->string('vital_pr')->nullable();
            $table->string('vital_rr')->nullable();

            $table->unsignedTinyInteger('gcs_eye')->nullable();
            $table->unsignedTinyInteger('gcs_verbal')->nullable();
            $table->unsignedTinyInteger('gcs_motor')->nullable();

            $table->json('allergies')->nullable();
            $table->string('allergy_other_text')->nullable();
            $table->json('family_history')->nullable();
            $table->string('family_history_other_text')->nullable();
            $table->json('medical_history')->nullable();
            $table->string('medical_history_other_text')->nullable();

            // Back page
            $table->text('diagnosis')->nullable();
            $table->text('doctors_order')->nullable();
            $table->text('interventions')->nullable();
            $table->text('soap_subjective')->nullable();
            $table->text('soap_objective')->nullable();
            $table->text('soap_assessment')->nullable();
            $table->text('soap_plan')->nullable();
            $table->string('attending_physician')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_consultations');
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthConsultation extends Model
{
    /**
     * Fixed checklist options straight from the paper Consultation Form —
     * stored as JSON arrays of these keys, same "fixed list, not
     * user-editable" convention as BuildingInspection::CATEGORIES.
     */
    const INJURY_TYPES = [
        'abrasion' => 'Abrasion',
        'contusion' => 'Contusion',
        'fracture' => 'Fracture',
        'laceration' => 'Laceration',
        'puncture' => 'Puncture',
        'sprain' => 'Sprain',
    ];

    const ALLERGIES = [
        'food' => 'Food',
        'drugs' => 'Drugs',
        'others' => 'Others',
    ];

    const FAMILY_HISTORY = [
        'ptb' => 'PTB',
        'cancer' => 'Cancer',
        'dm' => 'DM',
        'cardiovascular' => 'Cardiovascular',
        'others' => 'Others',
    ];

    const MEDICAL_HISTORY = [
        'seizure' => 'Seizure',
        'cardio' => 'Cardio',
        'neuro' => 'Neuro',
        'asthma' => 'Asthma',
        'ptb' => 'PTB',
        'surgery' => 'Surgery',
        'obgyne' => 'OB-Gyne',
        'others' => 'Others',
    ];

    /**
     * Glasgow Coma Scale — score => label, per the paper form's own table.
     * gcsTotal() sums whichever of these three groups are set; it is never
     * stored, same "trust the computation" convention as every other
     * derived total in this app (overtime, DTR hours, PDE totals).
     */
    const GCS_EYE = [
        4 => 'Opens Spontaneously',
        3 => 'Opens to Speech / Voice',
        2 => 'Opens to Pain',
        1 => 'No Response',
    ];

    const GCS_VERBAL = [
        5 => 'Oriented',
        4 => 'Confused',
        3 => 'Inappropriate Words',
        2 => 'Incomprehensible Words',
        1 => 'No Response',
    ];

    const GCS_MOTOR = [
        6 => 'Obeys Commands',
        5 => 'Localizes Pain',
        4 => 'Withdrawal Signs',
        3 => 'Flexion to Pain',
        2 => 'Extension to Pain',
        1 => 'No Response',
    ];

    protected $fillable = [
        'case_no',
        'consultation_date',
        'time_in',
        'time_out',
        'patient_name',
        'patient_age',
        'patient_sex',
        'patient_civil_status',
        'patient_address',
        'patient_birthday',
        'chief_complaint',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_no',
        'previous_consultation_date',
        'previous_diagnosis',
        'previous_medications',
        'previous_attending_physician',
        'mode_of_arrival',
        'has_injuries',
        'injury_types',
        'injury_other_text',
        'noi',
        'poi',
        'doi',
        'toi',
        'vital_bp',
        'vital_temp',
        'vital_pr',
        'vital_rr',
        'gcs_eye',
        'gcs_verbal',
        'gcs_motor',
        'allergies',
        'allergy_other_text',
        'family_history',
        'family_history_other_text',
        'medical_history',
        'medical_history_other_text',
        'diagnosis',
        'doctors_order',
        'interventions',
        'soap_subjective',
        'soap_objective',
        'soap_assessment',
        'soap_plan',
        'attending_physician',
        'created_by',
    ];

    protected $casts = [
        'consultation_date' => 'date',
        'patient_birthday' => 'date',
        'previous_consultation_date' => 'date',
        'doi' => 'date',
        'has_injuries' => 'boolean',
        'injury_types' => 'array',
        'allergies' => 'array',
        'family_history' => 'array',
        'medical_history' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function medicines()
    {
        return $this->hasMany(HealthConsultationMedicine::class);
    }

    /**
     * Sum of the three GCS groups — null unless all three are recorded,
     * since a partial GCS score doesn't mean anything clinically.
     */
    public function gcsTotal(): ?int
    {
        if (!$this->gcs_eye || !$this->gcs_verbal || !$this->gcs_motor) {
            return null;
        }

        return $this->gcs_eye + $this->gcs_verbal + $this->gcs_motor;
    }

    public static function generateCaseNo(): string
    {
        $year = now()->year;

        $lastId = static::max('id') + 1;

        return 'HC-' . $year . '-' . str_pad((string) $lastId, 4, '0', STR_PAD_LEFT);
    }
}

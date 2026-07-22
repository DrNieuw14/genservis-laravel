@php
    $consultation = $consultation ?? null;
    $old = fn ($field, $default = null) => old($field, $consultation?->{$field} ?? $default);
    $checked = fn ($group, $key) => in_array($key, old($group, $consultation?->{$group} ?? []));
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@if ($errors->any())
    <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $consultation ? route('health-consultations.update', $consultation->id) : route('health-consultations.store') }}">
    @csrf
    @if($consultation) @method('PUT') @endif

    <!-- VISIT INFO -->
    <h3 class="text-xl font-semibold text-gray-800 mb-4">Visit Information</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div>
            <label class="block mb-2 font-semibold">Date</label>
            <input type="text" id="consultation_date_picker" name="consultation_date"
                value="{{ $old('consultation_date', now()->toDateString()) }}"
                autocomplete="off" class="w-full border rounded-lg p-4" required>
        </div>
        <div>
            <label class="block mb-2 font-semibold">Time In</label>
            <input type="time" name="time_in" value="{{ $old('time_in') }}" class="w-full border rounded-lg p-4">
        </div>
        <div>
            <label class="block mb-2 font-semibold">Time Out</label>
            <input type="time" name="time_out" value="{{ $old('time_out') }}" class="w-full border rounded-lg p-4">
        </div>
    </div>

    <!-- PATIENT INFORMATION -->
    <h3 class="text-xl font-semibold text-gray-800 mb-4">Patient Information</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="md:col-span-2">
            <label class="block mb-2 font-semibold">Name</label>
            <input type="text" name="patient_name" value="{{ $old('patient_name') }}" class="w-full border rounded-lg p-4" required>
        </div>
        <div>
            <label class="block mb-2 font-semibold">Age</label>
            <input type="number" min="0" name="patient_age" value="{{ $old('patient_age') }}" class="w-full border rounded-lg p-4">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div>
            <label class="block mb-2 font-semibold">Sex</label>
            <select name="patient_sex" class="w-full border rounded-lg p-4">
                <option value="">-- Select --</option>
                <option value="Male" @selected($old('patient_sex') === 'Male')>Male</option>
                <option value="Female" @selected($old('patient_sex') === 'Female')>Female</option>
            </select>
        </div>
        <div>
            <label class="block mb-2 font-semibold">Civil Status</label>
            <select name="patient_civil_status" class="w-full border rounded-lg p-4">
                <option value="">-- Select --</option>
                @foreach(['Single', 'Married', 'Widowed', 'Separated'] as $cs)
                    <option value="{{ $cs }}" @selected($old('patient_civil_status') === $cs)>{{ $cs }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block mb-2 font-semibold">Birthday</label>
            <input type="text" id="birthday_picker" name="patient_birthday" value="{{ $old('patient_birthday') }}"
                autocomplete="off" class="w-full border rounded-lg p-4">
        </div>
    </div>

    <div class="mb-8">
        <label class="block mb-2 font-semibold">Address</label>
        <input type="text" name="patient_address" value="{{ $old('patient_address') }}" class="w-full border rounded-lg p-4">
    </div>

    <!-- CHIEF COMPLAINT -->
    <div class="mb-8">
        <label class="block mb-2 font-semibold text-lg">Chief Complaint</label>
        <textarea name="chief_complaint" rows="3" class="w-full border rounded-lg p-4">{{ $old('chief_complaint') }}</textarea>
    </div>

    <!-- EMERGENCY CONTACT / PREVIOUS CONSULTATION -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Contact Person (Emergency)</h3>

            <div class="space-y-4">
                <div>
                    <label class="block mb-2 font-semibold text-sm">Name</label>
                    <input type="text" name="emergency_contact_name" value="{{ $old('emergency_contact_name') }}" class="w-full border rounded-lg p-3">
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-sm">Relationship</label>
                    <input type="text" name="emergency_contact_relationship" value="{{ $old('emergency_contact_relationship') }}" class="w-full border rounded-lg p-3">
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-sm">Contact No.</label>
                    <input type="text" name="emergency_contact_no" value="{{ $old('emergency_contact_no') }}" class="w-full border rounded-lg p-3">
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Previous Consultation</h3>

            <div class="space-y-4">
                <div>
                    <label class="block mb-2 font-semibold text-sm">Date</label>
                    <input type="text" id="previous_consultation_date_picker" name="previous_consultation_date"
                        value="{{ $old('previous_consultation_date') }}" autocomplete="off" class="w-full border rounded-lg p-3">
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-sm">Diagnosis</label>
                    <input type="text" name="previous_diagnosis" value="{{ $old('previous_diagnosis') }}" class="w-full border rounded-lg p-3">
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-sm">Medications</label>
                    <input type="text" name="previous_medications" value="{{ $old('previous_medications') }}" class="w-full border rounded-lg p-3">
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-sm">Attending Physician</label>
                    <input type="text" name="previous_attending_physician" value="{{ $old('previous_attending_physician') }}" class="w-full border rounded-lg p-3">
                </div>
            </div>
        </div>

    </div>

    <!-- ASSESSMENT SECTION -->
    <div class="bg-gray-50 rounded-lg p-6 mb-8">

        <h3 class="text-xl font-semibold text-gray-800 mb-4">Assessment Section</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div>
                <label class="block mb-2 font-semibold text-sm">Mode of Arrival</label>
                <input type="text" name="mode_of_arrival" value="{{ $old('mode_of_arrival') }}"
                    placeholder="e.g. Ambulatory" class="w-full border rounded-lg p-3 mb-4">

                <label class="block mb-2 font-semibold text-sm">Patient with injuries?</label>
                <div class="flex gap-6 mb-4">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="has_injuries" value="1" id="injuriesYes" {{ $old('has_injuries') ? 'checked' : '' }}>
                        Yes
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="has_injuries" value="0" id="injuriesNo" {{ !$old('has_injuries') ? 'checked' : '' }}>
                        None
                    </label>
                </div>

                <div id="injuryDetails" class="{{ $old('has_injuries') ? '' : 'hidden' }}">
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        @foreach(\App\Models\HealthConsultation::INJURY_TYPES as $key => $label)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="injury_types[]" value="{{ $key }}" {{ $checked('injury_types', $key) ? 'checked' : '' }}>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    <input type="text" name="injury_other_text" value="{{ $old('injury_other_text') }}"
                        placeholder="Other injury (specify)" class="w-full border rounded-lg p-3 mb-4">

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 text-xs text-gray-500">NOI (Nature of Injury)</label>
                            <input type="text" name="noi" value="{{ $old('noi') }}" class="w-full border rounded-lg p-2">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs text-gray-500">POI (Place of Injury)</label>
                            <input type="text" name="poi" value="{{ $old('poi') }}" class="w-full border rounded-lg p-2">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs text-gray-500">DOI (Date of Injury)</label>
                            <input type="text" id="doi_picker" name="doi" value="{{ $old('doi') }}" autocomplete="off" class="w-full border rounded-lg p-2">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs text-gray-500">TOI (Time of Injury)</label>
                            <input type="time" name="toi" value="{{ $old('toi') }}" class="w-full border rounded-lg p-2">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-sm">Vital Signs</label>
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">BP</label>
                        <input type="text" name="vital_bp" value="{{ $old('vital_bp') }}" placeholder="e.g. 120/80" class="w-full border rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Temp</label>
                        <input type="text" name="vital_temp" value="{{ $old('vital_temp') }}" placeholder="°C" class="w-full border rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">PR</label>
                        <input type="text" name="vital_pr" value="{{ $old('vital_pr') }}" placeholder="bpm" class="w-full border rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">RR</label>
                        <input type="text" name="vital_rr" value="{{ $old('vital_rr') }}" placeholder="cpm" class="w-full border rounded-lg p-2">
                    </div>
                </div>

                <label class="block mb-2 font-semibold text-sm">Glasgow Coma Scale</label>
                <div class="space-y-2 mb-2">
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Eye</label>
                        <select name="gcs_eye" id="gcsEye" class="w-full border rounded-lg p-2">
                            <option value="">-- Select --</option>
                            @foreach(\App\Models\HealthConsultation::GCS_EYE as $score => $label)
                                <option value="{{ $score }}" @selected((int) $old('gcs_eye') === $score)>{{ $label }} ({{ $score }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Verbal</label>
                        <select name="gcs_verbal" id="gcsVerbal" class="w-full border rounded-lg p-2">
                            <option value="">-- Select --</option>
                            @foreach(\App\Models\HealthConsultation::GCS_VERBAL as $score => $label)
                                <option value="{{ $score }}" @selected((int) $old('gcs_verbal') === $score)>{{ $label }} ({{ $score }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Motor</label>
                        <select name="gcs_motor" id="gcsMotor" class="w-full border rounded-lg p-2">
                            <option value="">-- Select --</option>
                            @foreach(\App\Models\HealthConsultation::GCS_MOTOR as $score => $label)
                                <option value="{{ $score }}" @selected((int) $old('gcs_motor') === $score)>{{ $label }} ({{ $score }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="text-sm text-gray-600">Total Score: <span id="gcsTotal" class="font-semibold">-</span></p>
            </div>

        </div>

    </div>

    <!-- ALLERGIES / FAMILY / MEDICAL HISTORY -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">

        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Allergies</h3>
            @foreach(\App\Models\HealthConsultation::ALLERGIES as $key => $label)
                <label class="flex items-center gap-2 text-sm mb-2">
                    <input type="checkbox" name="allergies[]" value="{{ $key }}" {{ $checked('allergies', $key) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
            @endforeach
            <input type="text" name="allergy_other_text" value="{{ $old('allergy_other_text') }}"
                placeholder="Specify others" class="w-full border rounded-lg p-2 mt-1 text-sm">
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Family History</h3>
            @foreach(\App\Models\HealthConsultation::FAMILY_HISTORY as $key => $label)
                <label class="flex items-center gap-2 text-sm mb-2">
                    <input type="checkbox" name="family_history[]" value="{{ $key }}" {{ $checked('family_history', $key) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
            @endforeach
            <input type="text" name="family_history_other_text" value="{{ $old('family_history_other_text') }}"
                placeholder="Specify others" class="w-full border rounded-lg p-2 mt-1 text-sm">
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Medical History</h3>
            @foreach(\App\Models\HealthConsultation::MEDICAL_HISTORY as $key => $label)
                <label class="flex items-center gap-2 text-sm mb-2">
                    <input type="checkbox" name="medical_history[]" value="{{ $key }}" {{ $checked('medical_history', $key) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
            @endforeach
            <input type="text" name="medical_history_other_text" value="{{ $old('medical_history_other_text') }}"
                placeholder="Specify others" class="w-full border rounded-lg p-2 mt-1 text-sm">
        </div>

    </div>

    <!-- DIAGNOSIS / DOCTOR'S ORDER / INTERVENTIONS -->
    <h3 class="text-xl font-semibold text-gray-800 mb-4">Diagnosis & Management</h3>

    <div class="mb-6">
        <label class="block mb-2 font-semibold text-sm">Diagnosis</label>
        <textarea name="diagnosis" rows="2" class="w-full border rounded-lg p-4">{{ $old('diagnosis') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
            <label class="block mb-2 font-semibold text-sm">Doctor's Order</label>
            <textarea name="doctors_order" rows="4" class="w-full border rounded-lg p-4">{{ $old('doctors_order') }}</textarea>
        </div>
        <div>
            <label class="block mb-2 font-semibold text-sm">Interventions</label>
            <textarea name="interventions" rows="4" class="w-full border rounded-lg p-4">{{ $old('interventions') }}</textarea>
        </div>
    </div>

    <!-- SOAP NOTES -->
    <h3 class="text-xl font-semibold text-gray-800 mb-4">SOAP Notes</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
            <label class="block mb-2 font-semibold text-sm">S&gt; Subjective</label>
            <textarea name="soap_subjective" rows="3" class="w-full border rounded-lg p-4">{{ $old('soap_subjective') }}</textarea>
        </div>
        <div>
            <label class="block mb-2 font-semibold text-sm">O&gt; Objective</label>
            <textarea name="soap_objective" rows="3" class="w-full border rounded-lg p-4">{{ $old('soap_objective') }}</textarea>
        </div>
        <div>
            <label class="block mb-2 font-semibold text-sm">A&gt; Assessment</label>
            <textarea name="soap_assessment" rows="3" class="w-full border rounded-lg p-4">{{ $old('soap_assessment') }}</textarea>
        </div>
        <div>
            <label class="block mb-2 font-semibold text-sm">P&gt; Plan</label>
            <textarea name="soap_plan" rows="3" class="w-full border rounded-lg p-4">{{ $old('soap_plan') }}</textarea>
        </div>
    </div>

    <div class="mb-8">
        <label class="block mb-2 font-semibold">Attending Physician</label>
        <input type="text" name="attending_physician"
            value="{{ $old('attending_physician', $consultation?->attending_physician ?? auth()->user()->personnel->fullname ?? '') }}"
            class="w-full border rounded-lg p-4 md:w-1/2">
    </div>

    <div class="flex items-center gap-4">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow">
            💾 Save Consultation
        </button>
        <a href="{{ $consultation ? route('health-consultations.show', $consultation->id) : route('health-consultations.index') }}"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-8 py-3 rounded-lg">
            Cancel
        </a>
    </div>

</form>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

    flatpickr("#consultation_date_picker", { altInput: true, altFormat: "M j, Y", dateFormat: "Y-m-d", defaultDate: document.getElementById('consultation_date_picker').value });
    flatpickr("#birthday_picker", { altInput: true, altFormat: "M j, Y", dateFormat: "Y-m-d", defaultDate: document.getElementById('birthday_picker').value });
    flatpickr("#previous_consultation_date_picker", { altInput: true, altFormat: "M j, Y", dateFormat: "Y-m-d", defaultDate: document.getElementById('previous_consultation_date_picker').value });
    flatpickr("#doi_picker", { altInput: true, altFormat: "M j, Y", dateFormat: "Y-m-d", defaultDate: document.getElementById('doi_picker').value });

    document.getElementById('injuriesYes').addEventListener('change', toggleInjuryDetails);
    document.getElementById('injuriesNo').addEventListener('change', toggleInjuryDetails);

    function toggleInjuryDetails() {
        document.getElementById('injuryDetails').classList.toggle('hidden', !document.getElementById('injuriesYes').checked);
    }

    function updateGcsTotal() {
        const eye = parseInt(document.getElementById('gcsEye').value) || 0;
        const verbal = parseInt(document.getElementById('gcsVerbal').value) || 0;
        const motor = parseInt(document.getElementById('gcsMotor').value) || 0;

        const total = document.getElementById('gcsTotal');

        if (eye && verbal && motor) {
            total.textContent = (eye + verbal + motor) + ' / 15';
        } else {
            total.textContent = '-';
        }
    }

    ['gcsEye', 'gcsVerbal', 'gcsMotor'].forEach(id => {
        document.getElementById(id).addEventListener('change', updateGcsTotal);
    });

    updateGcsTotal();

</script>

<?php

namespace App\Http\Controllers;

use App\Models\ClinicMedicine;
use App\Models\HealthConsultation;
use App\Models\HealthConsultationMedicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthConsultationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $consultations = HealthConsultation::query()
            ->when($search, fn ($q) => $q->where('patient_name', 'like', "%{$search}%")
                ->orWhere('case_no', 'like', "%{$search}%")
                ->orWhere('chief_complaint', 'like', "%{$search}%"))
            ->latest('consultation_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('health_consultations.index', compact('consultations', 'search'));
    }

    public function create()
    {
        return view('health_consultations.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $consultation = HealthConsultation::create($validated + [
            'case_no' => HealthConsultation::generateCaseNo(),
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('health-consultations.show', $consultation->id)
            ->with('success', 'Consultation record saved.');
    }

    public function show($id)
    {
        $consultation = HealthConsultation::with(['creator', 'medicines.medicine'])->findOrFail($id);

        $availableMedicines = ClinicMedicine::orderBy('name')->get();

        return view('health_consultations.show', compact('consultation', 'availableMedicines'));
    }

    public function edit($id)
    {
        $consultation = HealthConsultation::findOrFail($id);

        return view('health_consultations.edit', compact('consultation'));
    }

    public function update(Request $request, $id)
    {
        $consultation = HealthConsultation::findOrFail($id);

        $validated = $this->validated($request);

        $consultation->update($validated);

        return redirect()
            ->route('health-consultations.show', $consultation->id)
            ->with('success', 'Consultation record updated.');
    }

    public function destroy($id)
    {
        $consultation = HealthConsultation::findOrFail($id);
        $consultation->delete();

        return redirect()
            ->route('health-consultations.index')
            ->with('success', 'Consultation record deleted.');
    }

    public function print($id)
    {
        $consultation = HealthConsultation::with(['creator', 'medicines'])->findOrFail($id);

        return view('health_consultations.print', compact('consultation'));
    }

    /**
     * Logs a medicine as dispensed to this patient and decrements the
     * clinic's live stock count. Stock is clamped at 0 rather than going
     * negative — if the requested quantity exceeds what's on hand, the
     * dispensing is still recorded (a nurse in a real clinic isn't blocked
     * by a stale count) but a warning is flashed so Kris knows to recount.
     */
    public function dispenseMedicine(Request $request, $id)
    {
        $consultation = HealthConsultation::findOrFail($id);

        $validated = $request->validate([
            'clinic_medicine_id' => 'required|exists:clinic_medicines,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ], [
            'clinic_medicine_id.required' => 'Please select a medicine.',
        ]);

        $medicine = ClinicMedicine::findOrFail($validated['clinic_medicine_id']);

        $consultation->medicines()->create([
            'clinic_medicine_id' => $medicine->id,
            'medicine_name' => $medicine->name . ($medicine->brand ? " ({$medicine->brand})" : ''),
            'unit' => $medicine->unit,
            'quantity' => $validated['quantity'],
            'notes' => $validated['notes'] ?? null,
            'dispensed_by' => Auth::id(),
        ]);

        $stockBefore = $medicine->current_stock;

        $medicine->update([
            'current_stock' => max(0, $stockBefore - $validated['quantity']),
        ]);

        $message = 'Medicine dispensed and stock updated.';

        if ($validated['quantity'] > $stockBefore) {
            $message .= " Warning: only {$stockBefore} {$medicine->unit} of {$medicine->name} was on record — stock has been set to 0. Please recount physical stock.";
        }

        return back()->with('success', $message);
    }

    /**
     * Undo a dispensing entry — restores the quantity back to the medicine's
     * current stock (if the medicine record still exists) and removes the
     * log entry.
     */
    public function destroyMedicine($id, $medicineLogId)
    {
        $log = HealthConsultationMedicine::where('health_consultation_id', $id)->findOrFail($medicineLogId);

        if ($log->medicine) {
            $log->medicine->increment('current_stock', $log->quantity);
        }

        $log->delete();

        return back()->with('success', 'Dispensing entry removed and stock restored.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'consultation_date' => 'required|date',
            'time_in' => 'nullable',
            'time_out' => 'nullable',

            'patient_name' => 'required|string|max:150',
            'patient_age' => 'nullable|integer|min:0|max:150',
            'patient_sex' => 'nullable|in:Male,Female',
            'patient_civil_status' => 'nullable|string|max:50',
            'patient_address' => 'nullable|string|max:255',
            'patient_birthday' => 'nullable|date',

            'chief_complaint' => 'nullable|string|max:1000',

            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'emergency_contact_no' => 'nullable|string|max:50',

            'previous_consultation_date' => 'nullable|date',
            'previous_diagnosis' => 'nullable|string|max:255',
            'previous_medications' => 'nullable|string|max:255',
            'previous_attending_physician' => 'nullable|string|max:150',

            'mode_of_arrival' => 'nullable|string|max:100',
            'has_injuries' => 'nullable|boolean',
            'injury_types' => 'nullable|array',
            'injury_types.*' => 'in:' . implode(',', array_keys(HealthConsultation::INJURY_TYPES)),
            'injury_other_text' => 'nullable|string|max:255',
            'noi' => 'nullable|string|max:255',
            'poi' => 'nullable|string|max:255',
            'doi' => 'nullable|date',
            'toi' => 'nullable',

            'vital_bp' => 'nullable|string|max:20',
            'vital_temp' => 'nullable|string|max:20',
            'vital_pr' => 'nullable|string|max:20',
            'vital_rr' => 'nullable|string|max:20',

            'gcs_eye' => 'nullable|integer|in:' . implode(',', array_keys(HealthConsultation::GCS_EYE)),
            'gcs_verbal' => 'nullable|integer|in:' . implode(',', array_keys(HealthConsultation::GCS_VERBAL)),
            'gcs_motor' => 'nullable|integer|in:' . implode(',', array_keys(HealthConsultation::GCS_MOTOR)),

            'allergies' => 'nullable|array',
            'allergies.*' => 'in:' . implode(',', array_keys(HealthConsultation::ALLERGIES)),
            'allergy_other_text' => 'nullable|string|max:255',

            'family_history' => 'nullable|array',
            'family_history.*' => 'in:' . implode(',', array_keys(HealthConsultation::FAMILY_HISTORY)),
            'family_history_other_text' => 'nullable|string|max:255',

            'medical_history' => 'nullable|array',
            'medical_history.*' => 'in:' . implode(',', array_keys(HealthConsultation::MEDICAL_HISTORY)),
            'medical_history_other_text' => 'nullable|string|max:255',

            'diagnosis' => 'nullable|string|max:2000',
            'doctors_order' => 'nullable|string|max:2000',
            'interventions' => 'nullable|string|max:2000',
            'soap_subjective' => 'nullable|string|max:2000',
            'soap_objective' => 'nullable|string|max:2000',
            'soap_assessment' => 'nullable|string|max:2000',
            'soap_plan' => 'nullable|string|max:2000',
            'attending_physician' => 'nullable|string|max:150',
        ], [
            'patient_name.required' => 'Please enter the patient\'s name.',
            'consultation_date.required' => 'Please select the consultation date.',
        ]);

        // Checkbox groups: an unchecked group sends no key at all, so it
        // must be defaulted explicitly or "clear everything" would
        // silently no-op — same gotcha as Energy Report's measures_implemented.
        $validated['has_injuries'] = $request->boolean('has_injuries');
        $validated['injury_types'] = $validated['injury_types'] ?? [];
        $validated['allergies'] = $validated['allergies'] ?? [];
        $validated['family_history'] = $validated['family_history'] ?? [];
        $validated['medical_history'] = $validated['medical_history'] ?? [];

        return $validated;
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\AdmissionYear;
use App\Models\ClinicMedicine;
use App\Models\FinalAdmission;
use App\Models\HealthConsultation;
use App\Models\HealthConsultationMedicine;
use App\Models\Notification;
use App\Models\Personnel;
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
        return view('health_consultations.create', [
            'selectedPatientForJs' => null,
        ]);
    }

    // Live search behind the Quick-Fill picker — scaled deliberately: the
    // Final List of Admission and Personnel roster are searched server-side
    // (small LIMIT, indexed by name) instead of ever shipping the full list
    // to the browser, so this stays fast whether the roster is 60 students
    // or 6,000.
    public function searchStudents(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $activeYearId = AdmissionYear::where('is_active', true)->value('id');

        $students = FinalAdmission::with('applicant')
            ->when($activeYearId, fn ($query) => $query->where('admission_year_id', $activeYearId))
            ->whereHas('applicant', function ($query) use ($q) {
                $query->where('given_name', 'like', "%{$q}%")
                    ->orWhere('middle_name', 'like', "%{$q}%")
                    ->orWhere('family_name', 'like', "%{$q}%");
            })
            ->limit(20)
            ->get()
            ->filter(fn ($fa) => $fa->applicant)
            ->map(fn ($fa) => $this->studentOption($fa))
            ->values();

        return response()->json($students);
    }

    public function searchEmployees(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $employees = Personnel::with(['positionRecord', 'profile', 'contact'])
            ->where('fullname', 'like', "%{$q}%")
            ->orderBy('fullname')
            ->limit(20)
            ->get()
            ->map(fn ($p) => $this->employeeOption($p))
            ->values();

        return response()->json($employees);
    }

    private function studentOption(FinalAdmission $fa): array
    {
        $a = $fa->applicant;

        return [
            'id' => $a->id,
            'name' => $a->fullName(),
            'program' => $fa->program_name,
            'sex' => $a->sex,
            'birthday' => $a->date_of_birth?->toDateString(),
            'age' => $a->date_of_birth?->age,
            'address' => collect([$a->house_no, $a->street, $a->barangay, $a->municipality, $a->province])
                ->filter()
                ->implode(', '),
        ];
    }

    // Pulls whatever HR Employee Master data exists (Employee Profile /
    // Contact) so a consultation for staff/faculty can auto-fill as much
    // as a student's admission record does — falls back to name-only when
    // an employee has no HR profile filled in yet (most don't, currently).
    private function employeeOption(Personnel $p): array
    {
        $profile = $p->profile;
        $contact = $p->contact;

        return [
            'id' => $p->id,
            'name' => $p->fullname,
            'position' => $p->positionRecord->position_name ?? null,
            'sex' => $profile?->gender,
            'birthday' => $profile?->birthdate ? \Illuminate\Support\Carbon::parse($profile->birthdate)->toDateString() : null,
            'age' => $profile?->birthdate ? \Illuminate\Support\Carbon::parse($profile->birthdate)->age : null,
            'civil_status' => $profile?->civil_status,
            'emergency_contact_name' => $contact?->emergency_contact_person,
            'emergency_contact_relationship' => $contact?->emergency_relationship,
            'emergency_contact_no' => $contact?->emergency_contact_number,
        ];
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $consultation = HealthConsultation::create($validated + [
            'case_no' => HealthConsultation::generateCaseNo(),
            'created_by' => Auth::id(),
        ]);

        $this->notifyPatientRecorded($consultation, 'Health Consultation Recorded', 'You visited Health Services for a consultation on '
            . $consultation->consultation_date->format('M d, Y') . '.');

        return redirect()
            ->route('health-consultations.show', $consultation->id)
            ->with('success', 'Consultation record saved.');
    }

    // 🔔 Notify the employee this visit was for — visible on their own
    // "My Health Consultations" self-service page. Only fires when the
    // patient is a linked employee with a real login (not a student, who
    // has no user account, or a free-text walk-in patient). Shared by
    // store() (new visit recorded) and update() (consultation finalized/
    // completed by Kris after further editing).
    private function notifyPatientRecorded(HealthConsultation $consultation, string $title, string $message): void
    {
        if (!$consultation->personnel_id) {
            return;
        }

        $personnel = Personnel::find($consultation->personnel_id);

        if (!$personnel || !$personnel->user_id) {
            return;
        }

        $notif = Notification::create([
            'user_id' => $personnel->user_id,
            'type' => 'health_consultation',
            'title' => $title,
            'url' => route('health-consultations.mine'),
            'message' => $message,
            'is_read' => 0,
        ]);

        event(new NewNotificationEvent($notif));
    }

    // Self-service: an employee viewing their own visit history — same
    // pattern as My Borrowed Equipment / My Property Accountability.
    public function mine()
    {
        $personnel = Personnel::where('user_id', Auth::id())->first();

        abort_if(!$personnel, 403);

        $consultations = HealthConsultation::where('personnel_id', $personnel->id)
            ->latest('consultation_date')
            ->latest('id')
            ->get();

        return view('health_consultations.mine', compact('consultations'));
    }

    public function show($id)
    {
        $consultation = HealthConsultation::with(['creator', 'medicines.medicine'])->findOrFail($id);

        $this->authorizeView($consultation);

        // A patient viewing their own record doesn't manage clinic stock —
        // skip building/exposing inventory data entirely for them, and hide
        // the Dispense Medicine controls in the view.
        $canManage = Auth::user()->hasPermission('manage-health-consultations');

        $medicinesForJs = collect();
        $expiringSoonCount = 0;
        $expiredCount = 0;
        $outOfStockCount = 0;

        if ($canManage) {

            $medicines = ClinicMedicine::orderBy('name')->get();

            $medicinesForJs = $medicines->map(fn ($m) => $this->medicineOption($m))->values();

            $expiringSoonCount = $medicines->filter(fn ($m) => $m->isExpiringSoon() && !$m->isExpired())->count();
            $expiredCount = $medicines->filter(fn ($m) => $m->isExpired())->count();
            $outOfStockCount = $medicines->filter(fn ($m) => $m->isOutOfStock())->count();
        }

        return view('health_consultations.show', compact(
            'consultation',
            'canManage',
            'medicinesForJs',
            'expiringSoonCount',
            'expiredCount',
            'outOfStockCount'
        ));
    }

    private function authorizeView(HealthConsultation $consultation): void
    {
        $user = Auth::user();

        if ($user->hasPermission('manage-health-consultations')) {
            return;
        }

        $personnel = Personnel::where('user_id', $user->id)->first();

        abort_if(!$personnel || $consultation->personnel_id !== $personnel->id, 403);
    }

    // Shared shape for the Dispense Medicine picker — status is computed
    // fresh every time (never stored), same "trust the computation"
    // convention as everything else derived in this app.
    private function medicineOption(ClinicMedicine $m): array
    {
        $status = 'ok';

        if ($m->isOutOfStock()) {
            $status = 'out_of_stock';
        } elseif ($m->isExpired()) {
            $status = 'expired';
        } elseif ($m->isExpiringSoon()) {
            $status = 'expiring_soon';
        }

        return [
            'id' => $m->id,
            'name' => $m->name,
            'brand' => $m->brand,
            'unit' => $m->unit,
            'stock' => $m->current_stock,
            'expiration_date' => $m->expiration_date?->format('M Y'),
            'status' => $status,
            'blocked' => in_array($status, ['out_of_stock', 'expired']),
        ];
    }

    public function edit($id)
    {
        $consultation = HealthConsultation::with(['admissionApplicant', 'personnel.positionRecord'])->findOrFail($id);

        // The linked student/employee (if any) needs to appear pre-selected
        // in the picker even though the dropdown no longer preloads the
        // full roster — so just this one record is sent along.
        $selectedPatientForJs = null;

        if ($consultation->admissionApplicant) {
            $selectedPatientForJs = [
                'value' => 'student-' . $consultation->admissionApplicant->id,
                'text' => $consultation->admissionApplicant->fullName(),
            ];
        } elseif ($consultation->personnel) {
            $selectedPatientForJs = [
                'value' => 'faculty-' . $consultation->personnel->id,
                'text' => $consultation->personnel->fullname,
            ];
        }

        return view('health_consultations.edit', [
            'consultation' => $consultation,
            'selectedPatientForJs' => $selectedPatientForJs,
        ]);
    }

    public function update(Request $request, $id)
    {
        $consultation = HealthConsultation::findOrFail($id);

        $validated = $this->validated($request);

        $consultation->update($validated);

        $this->notifyPatientRecorded($consultation, 'Health Consultation Completed', 'Your health consultation on '
            . $consultation->consultation_date->format('M d, Y') . ' has been completed. View your record for details.');

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

        $this->authorizeView($consultation);

        return view('health_consultations.print', compact('consultation'));
    }

    /**
     * Logs one or more medicines as dispensed to this patient in a single
     * visit (mirrors Material Request's multi-item cart) and decrements
     * each one's live stock count. Stock is clamped at 0 rather than going
     * negative — if the requested quantity exceeds what's on hand, the
     * dispensing is still recorded (a nurse in a real clinic isn't blocked
     * by a stale count) but a warning is flashed so Kris knows to recount.
     */
    public function dispenseMedicine(Request $request, $id)
    {
        $consultation = HealthConsultation::findOrFail($id);

        // Drop any incomplete rows (no medicine picked, or no quantity) —
        // same "unused Add Row is not an error" convention as Material
        // Request's store().
        $medicineIds = $request->input('clinic_medicine_id', []);
        $quantities = $request->input('quantity', []);
        $notes = $request->input('notes', []);

        $cleanMedicineIds = [];
        $cleanQuantities = [];
        $cleanNotes = [];

        foreach ($medicineIds as $index => $medicineId) {

            $qty = $quantities[$index] ?? null;

            if (!empty($medicineId) && !empty($qty)) {
                $cleanMedicineIds[] = $medicineId;
                $cleanQuantities[] = $qty;
                $cleanNotes[] = $notes[$index] ?? null;
            }
        }

        $request->merge([
            'clinic_medicine_id' => $cleanMedicineIds,
            'quantity' => $cleanQuantities,
        ]);

        $validated = $request->validate([
            'clinic_medicine_id' => 'required|array|min:1',
            'clinic_medicine_id.*' => 'required|exists:clinic_medicines,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:255',
        ], [
            'clinic_medicine_id.required' => 'Please select at least one medicine.',
            'clinic_medicine_id.min' => 'Please select at least one medicine.',
        ]);

        // Block Out-of-Stock/Expired before writing anything — defense in
        // depth beyond the picker already disabling those options, same
        // pattern as every other "recheck server-side" guard in this app.
        foreach ($validated['clinic_medicine_id'] as $medicineId) {

            $medicine = ClinicMedicine::find($medicineId);

            if ($medicine->isExpired()) {
                return back()->withInput()->with('error', $medicine->name . ' is expired and cannot be dispensed.');
            }

            if ($medicine->isOutOfStock()) {
                return back()->withInput()->with('error', $medicine->name . ' is out of stock and cannot be dispensed.');
            }
        }

        $lowStockWarnings = [];

        foreach ($validated['clinic_medicine_id'] as $index => $medicineId) {

            $medicine = ClinicMedicine::find($medicineId);
            $quantity = $validated['quantity'][$index];

            $consultation->medicines()->create([
                'clinic_medicine_id' => $medicine->id,
                'medicine_name' => $medicine->name . ($medicine->brand ? " ({$medicine->brand})" : ''),
                'unit' => $medicine->unit,
                'quantity' => $quantity,
                'notes' => $cleanNotes[$index] ?? null,
                'dispensed_by' => Auth::id(),
            ]);

            $stockBefore = $medicine->current_stock;

            $medicine->update([
                'current_stock' => max(0, $stockBefore - $quantity),
            ]);

            if ($quantity > $stockBefore) {
                $lowStockWarnings[] = "only {$stockBefore} {$medicine->unit} of {$medicine->name} was on record — stock has been set to 0";
            }
        }

        $message = count($validated['clinic_medicine_id']) > 1
            ? 'Medicines dispensed and stock updated.'
            : 'Medicine dispensed and stock updated.';

        if (!empty($lowStockWarnings)) {
            $message .= ' Warning: ' . implode('; ', $lowStockWarnings) . '. Please recount physical stock.';
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

            'admission_applicant_id' => 'nullable|exists:admission_applicants,id',
            'personnel_id' => 'nullable|exists:personnel,id',

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

<?php

namespace App\Http\Controllers;

use App\Imports\AdmissionApplicantImport;
use App\Models\AdmissionApplicant;
use App\Models\AdmissionYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class AdmissionApplicantController extends Controller
{
    public function index(Request $request, $yearId)
    {
        $year = AdmissionYear::findOrFail($yearId);

        $search = $request->input('search');

        $applicants = $year->applicants()
            ->when($search, fn ($q) => $q->where('given_name', 'like', "%{$search}%")
                ->orWhere('family_name', 'like', "%{$search}%")
                ->orWhere('control_number', 'like', "%{$search}%"))
            ->orderBy('family_name')
            ->paginate(25)
            ->withQueryString();

        return view('admission_applicants.index', compact('year', 'applicants', 'search'));
    }

    public function importForm($yearId)
    {
        $year = AdmissionYear::findOrFail($yearId);

        return view('admission_applicants.import', compact('year'));
    }

    public function importStore(Request $request, $yearId)
    {
        $year = AdmissionYear::findOrFail($yearId);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $import = new AdmissionApplicantImport($year->id);

        Excel::import($import, $request->file('file'));

        return redirect()
            ->route('admission-applicants.index', $year->id)
            ->with('success', "{$import->imported} applicant(s) imported/updated.")
            ->with('importSkipped', $import->skipped)
            ->with('importDuplicates', $import->possibleDuplicates);
    }

    public function show($yearId, $id)
    {
        $year = AdmissionYear::findOrFail($yearId);
        $applicant = $year->applicants()->findOrFail($id);

        return view('admission_applicants.show', compact('year', 'applicant'));
    }

    public function edit($yearId, $id)
    {
        $year = AdmissionYear::findOrFail($yearId);
        $applicant = $year->applicants()->findOrFail($id);

        return view('admission_applicants.edit', compact('year', 'applicant'));
    }

    public function update(Request $request, $yearId, $id)
    {
        $year = AdmissionYear::findOrFail($yearId);
        $applicant = $year->applicants()->findOrFail($id);

        $validated = $request->validate([
            'control_number' => [
                'required', 'string', 'max:50',
                Rule::unique('admission_applicants', 'control_number')
                    ->where('admission_year_id', $year->id)
                    ->ignore($applicant->id),
            ],
            'given_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'family_name' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'sex' => 'nullable|string|max:20',
            'house_no' => 'nullable|string|max:100',
            'street' => 'nullable|string|max:150',
            'barangay' => 'nullable|string|max:150',
            'municipality' => 'nullable|string|max:150',
            'municipality_income_class' => 'nullable|string|max:50',
            'province' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'campus' => 'nullable|string|max:100',
            'program' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:150',
            'contact_number' => 'nullable|string|max:30',
        ]);

        $applicant->update($validated);

        return redirect()
            ->route('admission-applicants.show', [$year->id, $applicant->id])
            ->with('success', 'Applicant record updated.');
    }

    public function destroy($yearId, $id)
    {
        $year = AdmissionYear::findOrFail($yearId);
        $applicant = $year->applicants()->findOrFail($id);
        $applicant->delete();

        return redirect()
            ->route('admission-applicants.index', $year->id)
            ->with('success', 'Applicant removed.');
    }
}

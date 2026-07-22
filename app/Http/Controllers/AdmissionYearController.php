<?php

namespace App\Http\Controllers;

use App\Models\AdmissionYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdmissionYearController extends Controller
{
    public function index()
    {
        $years = AdmissionYear::withCount('applicants')
            ->latest('id')
            ->get();

        return view('admission_years.index', compact('years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100|unique:admission_years,label',
            'description' => 'nullable|string|max:500',
        ], [
            'label.required' => 'Please enter a label for this admission year (e.g. AY 2026-2027).',
            'label.unique' => 'An admission year with this label already exists.',
        ]);

        $year = AdmissionYear::create($validated + [
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admission-applicants.index', $year->id)
            ->with('success', 'Admission year created. You can now upload the applicant roster.');
    }

    public function destroy($id)
    {
        $year = AdmissionYear::findOrFail($id);
        $year->delete();

        return redirect()
            ->route('admission-years.index')
            ->with('success', 'Admission year and its applicant roster deleted.');
    }
}

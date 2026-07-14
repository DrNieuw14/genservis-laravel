<?php

namespace App\Http\Controllers;

use App\Models\EmployeeEducation;
use App\Models\Personnel;
use Illuminate\Http\Request;

class EmployeeEducationController extends Controller
{
    public function create(Personnel $personnel)
    {
        return view('employees.education.create', compact('personnel'));
    }

    public function store(Request $request, Personnel $employee)
    {
        $validated = $request->validate([
            'education_level' => 'required|string',
            'school_name' => 'required|string|max:255',
            'degree_course' => 'nullable|string|max:255',
            'highest_level' => 'nullable|string|max:255',
            'from_year' => 'nullable|digits:4',
            'to_year' => 'nullable|digits:4',
            'year_graduated' => 'nullable|digits:4',
            'honors' => 'nullable|string|max:255',
            'units_earned' => 'nullable|string|max:255',
        ]);

        $validated['personnel_id'] = $employee->id;

        EmployeeEducation::create($validated);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Educational background added successfully.');
    }

    public function edit(EmployeeEducation $employeeEducation)
    {
        return view('employees.education.edit', compact('employeeEducation'));
    }

    public function update(
    Request $request,
    Personnel $employee,
    EmployeeEducation $education
    )
    {
        $validated = $request->validate([
            'education_level' => 'required|string',
            'school_name' => 'required|string|max:255',
            'degree_course' => 'nullable|string|max:255',
            'highest_level' => 'nullable|string|max:255',
            'from_year' => 'nullable|digits:4',
            'to_year' => 'nullable|digits:4',
            'year_graduated' => 'nullable|digits:4',
            'honors' => 'nullable|string|max:255',
            'units_earned' => 'nullable|string|max:255',
        ]);

        $education->update($validated);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Educational background updated successfully.');
    }

    public function destroy(
    Personnel $employee,
    EmployeeEducation $education
    )
    {
        $education->delete();

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Educational background deleted successfully.');
    }
}
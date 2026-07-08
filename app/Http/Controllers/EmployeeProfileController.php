<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\EmployeeProfile;
use App\Models\Personnel;
use Illuminate\Http\Request;

class EmployeeProfileController extends Controller
{
    public function create(Personnel $employee)
    {
        return view('employees.profile.create', compact('employee'));
    }

    public function store(Request $request, Personnel $employee)
    {
        $validated = $request->validate([
            'birthdate'    => 'nullable|date',
            'gender'       => 'nullable|string|max:20',
            'civil_status' => 'nullable|string|max:50',
            'nationality'  => 'nullable|string|max:100',
            'religion'     => 'nullable|string|max:100',
            'blood_type'   => 'nullable|string|max:10',
        ]);

        if ($employee->profile) {
            return redirect()
                ->route('employees.show', $employee)
                ->with('error', 'Personal information already exists.');
        }

        $employee->profile()->create($validated);

        ActivityLogger::log(
            'Employee Master',
            'Created Personal Information',
            'Added personal information for ' . $employee->fullname
        );

        return redirect()
            ->route('employees.show', $employee)
            ->with(
                'success',
                'Personal information saved successfully.'
            );
    }

    public function edit(Personnel $employee)
    {
        return view('employees.profile.edit', compact('employee'));
    }

    public function update(Request $request, Personnel $employee)
    {
        $validated = $request->validate([
            'birthdate'     => 'nullable|date',
            'gender'        => 'nullable|string|max:20',
            'civil_status'  => 'nullable|string|max:50',
            'nationality'   => 'nullable|string|max:100',
            'religion'      => 'nullable|string|max:100',
            'blood_type'    => 'nullable|string|max:10',
        ]);

        $employee->profile()->updateOrCreate(
            [],
            $validated
        );

        ActivityLogger::log(
            'Employee Master',
            'Updated Personal Information',
            'Updated personal information for ' . $employee->fullname
        );

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Personal information updated successfully.');
    }
}
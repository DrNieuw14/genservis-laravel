<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\EmployeeContact;
use Illuminate\Http\Request;

class EmployeeContactController extends Controller
{
    public function create(Personnel $employee)
    {
        return view('employees.contact.create', compact('employee'));
    }

    public function store(Request $request, Personnel $employee)
    {
        $validated = $request->validate([

            'primary_email' => 'required|email|max:255',

            'alternate_email' => 'nullable|email|max:255',

            'mobile_number' => 'required|string|max:25',

            'telephone_number' => 'nullable|string|max:25',

            'emergency_contact_person' => 'required|string|max:255',

            'emergency_contact_number' => 'required|string|max:25',

            'emergency_relationship' => 'required|string|max:100',

        ]);

        $employee->contact()->create($validated);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Contact information saved successfully.');
    }

    public function edit(Personnel $employee)
    {
        return view('employees.contact.edit', [
            'employee' => $employee,
            'contact' => $employee->contact,
        ]);
    }

    public function update(Request $request, Personnel $employee)
    {
        $validated = $request->validate([

            'primary_email' => 'required|email|max:255',

            'alternate_email' => 'nullable|email|max:255',

            'mobile_number' => 'required|string|max:25',

            'telephone_number' => 'nullable|string|max:25',

            'emergency_contact_person' => 'required|string|max:255',

            'emergency_contact_number' => 'required|string|max:25',

            'emergency_relationship' => 'required|string|max:100',

        ]);

        $employee->contact()->update($validated);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Contact information updated successfully.');
    }
}
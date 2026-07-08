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
                $validated = $request->validate(

    [

        'primary_email' => 'required|email|max:255',

        'alternate_email' => 'nullable|email|max:255',

        'mobile_number' => [
            'required',
            'digits:11',
            'regex:/^09\d{9}$/',
        ],

        'telephone_number' => [
            'nullable',
            'regex:/^[0-9()\-\s]+$/',
            'max:20',
        ],

        'emergency_contact_person' => 'required|string|max:255',

        'emergency_contact_number' => [
            'required',
            'digits:11',
            'regex:/^09\d{9}$/',
        ],

        'emergency_relationship' => 'required|string|max:100',

    ],

    [

        'mobile_number.regex' =>
            'Mobile number must start with 09 and contain exactly 11 digits.',

        'mobile_number.digits' =>
            'Mobile number must be exactly 11 digits.',

        'emergency_contact_number.regex' =>
            'Emergency contact number must start with 09 and contain exactly 11 digits.',

        'emergency_contact_number.digits' =>
            'Emergency contact number must be exactly 11 digits.',

        'telephone_number.regex' =>
            'Telephone number contains invalid characters.',

    ]

);

        EmployeeContact::updateOrCreate(
            [
                'personnel_id' => $employee->id,
            ],
            array_merge($validated, [
                'personnel_id' => $employee->id,
            ])
        );

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
        $validated = $request->validate(

    [

        'primary_email' => 'required|email|max:255',

        'alternate_email' => 'nullable|email|max:255',

        'mobile_number' => [
            'required',
            'digits:11',
            'regex:/^09\d{9}$/',
        ],

        'telephone_number' => [
            'nullable',
            'regex:/^[0-9()\-\s]+$/',
            'max:20',
        ],

        'emergency_contact_person' => 'required|string|max:255',

        'emergency_contact_number' => [
            'required',
            'digits:11',
            'regex:/^09\d{9}$/',
        ],

        'emergency_relationship' => 'required|string|max:100',

    ],

    [

        'mobile_number.regex' =>
            'Mobile number must start with 09 and contain exactly 11 digits.',

        'mobile_number.digits' =>
            'Mobile number must be exactly 11 digits.',

        'emergency_contact_number.regex' =>
            'Emergency contact number must start with 09 and contain exactly 11 digits.',

        'emergency_contact_number.digits' =>
            'Emergency contact number must be exactly 11 digits.',

        'telephone_number.regex' =>
            'Telephone number contains invalid characters.',

    ]

);

        if (! $employee->contact) {
            return redirect()
                ->route('employees.contact.create', $employee)
                ->with('error', 'Please add contact information first.');
        }

        $employee->contact->update($validated);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Contact information updated successfully.');
    
    
            }

            

}
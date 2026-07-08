<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $employees = Personnel::with([
            'user.systemRole',
            'employmentType',
            'departmentRecord',
            'positionRecord',
        ])
            ->when($search, function ($query) use ($search) {
                $query->where('employee_id', 'like', "%{$search}%")
                      ->orWhere('fullname', 'like', "%{$search}%");
            })
            ->orderBy('fullname')
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact(
            'employees',
            'search'
        ));
    }

    public function show(Personnel $employee)
    {
        $employee->load([
            'user.systemRole',
            'employmentType',
            'departmentRecord',
            'positionRecord',
            'profile',
            'contact',
        ]);

        return view('employees.show', compact('employee'));
    }
}
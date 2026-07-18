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
            'user.additionalRoles',
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

    /**
     * Scoped roster for the General Services Officer: utility personnel
     * (Utility Worker, Groundskeeper) plus electrical/maintenance staff,
     * without granting the broader Employee Master permission.
     */
    public function utilityStaff(Request $request)
    {
        $search = $request->search;

        $employees = Personnel::with([
            'user.systemRole',
            'user.additionalRoles',
            'employmentType',
            'departmentRecord',
            'positionRecord',
        ])
            ->where(function ($query) {
                $query->whereHas('employmentType', fn ($q) => $q->where('name', 'Utility Personnel'))
                    ->orWhereHas('positionRecord', fn ($q) => $q
                        ->where('position_name', 'like', '%lectric%')
                        ->orWhere('position_name', 'like', '%aintenance%'));
            })
            ->when($search, function ($query) use ($search) {
                $query->where(fn ($q) => $q
                    ->where('employee_id', 'like', "%{$search}%")
                    ->orWhere('fullname', 'like', "%{$search}%"));
            })
            ->orderBy('fullname')
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', [
            'employees' => $employees,
            'search' => $search,
            'pageTitle' => '🧰 Utility & Maintenance Staff',
            'pageDescription' => 'Utility personnel and electrical/maintenance staff under General Services.',
            'backRoute' => route('personnel.dashboard'),
        ]);
    }

    public function show(Personnel $employee)
    {
        $employee->load([

            /*
            |--------------------------------------------------------------------------
            | User & Employment
            |--------------------------------------------------------------------------
            */
            'user.systemRole',
            'user.additionalRoles',
            'employmentType',
            'departmentRecord',
            'positionRecord',

            /*
            |--------------------------------------------------------------------------
            | Employee Information
            |--------------------------------------------------------------------------
            */
            'profile',
            'contact',

            /*
            |--------------------------------------------------------------------------
            | Employee 201 File
            |--------------------------------------------------------------------------
            */
            'educations',

        ]);

        return view('employees.show', compact('employee'));
    }

    public function create(Personnel $employee)
    {
        return view('employees.education.create', compact('employee'));
    }
}
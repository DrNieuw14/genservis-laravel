<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->get();

        return view('supervisor.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('supervisor.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
        ]);

        Department::create([
            'department_name' => $request->department_name,
            'department_code' => $request->department_code,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('supervisor.departments.index')
            ->with('success', 'Department added successfully.');
    }
}
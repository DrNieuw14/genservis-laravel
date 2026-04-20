<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonnelController extends Controller
{
    public function create()
    {
        // Check if personnel profile already exists
        $existing = Personnel::where('user_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('personnel.dashboard')
                ->with('info', 'Your profile already exists.');
        }
        return view('personnel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'   => ['required', 'string', 'max:50', 'unique:personnel,employee_id'],
            'fullname'      => ['required', 'string', 'max:100'],
            'position'      => ['nullable', 'string', 'max:100'],
            'department'    => ['nullable', 'string', 'max:100'],
            'assigned_area' => ['nullable', 'string', 'max:100'],
        ]);

        Personnel::create([
            'employee_id'   => $request->employee_id,
            'fullname'      => $request->fullname,
            'position'      => $request->position,
            'department'    => $request->department,
            'assigned_area' => $request->assigned_area,
            'status'        => 'Active',
            'user_id'       => Auth::id(),
        ]);

        return redirect()->route('personnel.dashboard')
            ->with('success', 'Personnel profile created successfully!');
    }

    public function dashboard()
    {
        $personnel = Personnel::where('user_id', Auth::id())->first();
        return view('personnel.dashboard', compact('personnel'));
    }
}
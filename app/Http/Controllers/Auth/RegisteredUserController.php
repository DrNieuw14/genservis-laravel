<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewUserRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        // ✅ Create User FIRST
        $user = \App\Models\User::create([
            'first_name' => $request->first_name,
            'middle_initial' => $request->middle_initial,
            'last_name' => $request->last_name,
            'fullname' => $request->first_name . ' ' . $request->last_name,
            'username' => $request->username,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'personnel',
            'status' => 'pending',
        ]);

        // ✅ Create Personnel
        $personnel = \App\Models\Personnel::create([
            'employee_id' => 'EMP' . rand(1000,9999), // simple generator
            'fullname' => $user->fullname,
            'position' => 'Staff',
            'department' => 'Maintenance',
        ]);

        // ✅ LINK USER → PERSONNEL
        $user->personnel_id = $personnel->id;
        $user->save();

        return redirect()->route('login')->with('success', 'Registered successfully. Wait for approval.');
    }
}
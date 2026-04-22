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
 use Carbon\Carbon; // ADD THIS AT TOP

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

   

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'username' => 'required|string|max:255|unique:users',
        'password' => 'required|confirmed|min:6',

        'birth_month' => 'required',
        'birth_day' => 'required',
        'birth_year' => 'required',
    ]);

    // ✅ Build birthdate
    $birthdate = Carbon::create(
        $request->birth_year,
        $request->birth_month,
        $request->birth_day
    );

    // ✅ Create User
    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'username' => $request->username,
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),

        'birthdate' => $birthdate,
        'birth_month' => $birthdate->format('F'),
        'age' => $birthdate->age,

        'role' => 'personnel',
        'status' => 'pending',
    ]);

    // ✅ Create Personnel (linked)
    \App\Models\Personnel::create([
        'employee_id' => 'EMP' . rand(1000,9999),
        'fullname' => $user->name,
        'position' => 'Staff',
        'department' => 'Maintenance',
        'user_id' => $user->id
    ]);

    return redirect()->route('login')->with('success', 'Registered successfully. Wait for approval.');
}
}
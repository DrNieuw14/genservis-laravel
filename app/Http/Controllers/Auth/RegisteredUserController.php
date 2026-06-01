<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Models\Notification;

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
    $user = User::create([
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
        'employee_id' => 'EMP' . str_pad(
            \App\Models\Personnel::count() + 1,
            5,
            '0',
            STR_PAD_LEFT
        ),

        'fullname' => $user->name,
        'position' => 'Staff',
        'department' => 'Maintenance',
        'user_id' => $user->id,
        'status' => 'Active'
    ]);

    $supervisor = User::where('role', 'supervisor')->first();

    if ($supervisor) {

        Notification::create([
            'user_id' => $supervisor->id,
            'type' => 'user_registration',
            'title' => 'New User Registration',
            'message' => $user->name . ' registered and needs approval',
            'url' => route('admin.users.pending', [], false),
            'is_read' => 0,
        ]);

    }

    return redirect()
        ->route('login')
        ->with(
            'success',
            'Registration successful! Waiting for admin approval.'
        );
    }
}

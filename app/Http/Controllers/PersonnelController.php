<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PersonnelController extends Controller
{
    public function create()
    {
        return view('personnel.create');
    }

    

    public function store(Request $request)
    
    {
        $request->validate([
    'fullname' => 'required|string|max:255',
    'employee_id' => 'required|unique:personnel,employee_id',
    'position' => 'required|string',
    'department' => 'required|string',
]);
        // ✅ Create USER FIRST
        $user = User::create([
            'name' => $request->fullname,
            'username' => strtolower(str_replace(' ', '', $request->fullname)),
            'email' => strtolower(str_replace(' ', '', $request->fullname)) . '@genservis.local',
            'password' => Hash::make('123456'),
            'role' => 'personnel'
        ]);

        // ✅ Create PERSONNEL
        Personnel::create([
            'employee_id' => $request->employee_id,
            'fullname' => $request->fullname,
            'position' => $request->position,
            'department' => $request->department,
            'user_id' => $user->id
        ]);

        return redirect('/personnel/create')->with([
            'success' => 'Personnel created successfully!',
            'username' => $user->username,
            'password' => '123456'
        ]);
    }
}
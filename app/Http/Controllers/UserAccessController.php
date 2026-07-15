<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;

class UserAccessController extends Controller
{
    public function index()
    {
        $query = User::with([
            'systemRole',
            'personnel.departmentRecord',
            'personnel.positionRecord',
            'personnel.employmentType',
        ]);

        if (request('search')) {

            $search = request('search');

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhereHas('personnel', function ($personnel) use ($search) {

                        $personnel->where('employee_id', 'like', "%{$search}%")
                                ->orWhere('fullname', 'like', "%{$search}%");

                });

            });

        }

        $users = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.user-access.index', [

            'users' => $users,

            'totalUsers' => User::count(),

            'activeUsers' => User::where('status', 'approved')->count(),

            'pendingUsers' => User::where('status', 'pending')->count(),

            'roles' => Role::orderBy('name')->get(),

        ]);
    }
}
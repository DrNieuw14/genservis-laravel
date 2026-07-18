<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use App\Models\Personnel;
use App\Models\Notification;

use App\Models\EmploymentType;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;



class UserApprovalController extends Controller
{
   
    public function index()
    {
        $users = User::where('status', 'pending')
            ->where('role', 'personnel')
            ->latest()
            ->get();

        return view('admin.users.pending', [
            'users' => $users,
            'approvedCount' => User::where('status', 'approved')->count(),
            'rejectedCount' => User::where('status', 'rejected')->count(),
            'pendingCount'  => User::where('status', 'pending')->count(),
        ]);
    }

    // ✅ ADD THIS METHOD HERE
    public function onboarding(User $user)
    {
        return view('admin.users.onboarding', [
            'user' => $user,

            'employmentTypes' => EmploymentType::where('is_active', 1)
                ->orderBy('name')
                ->get(),

            'departments' => Department::orderBy('department_name')
                ->get(),

            'positions' => Position::where('is_active', 1)
                ->orderBy('position_name')
                ->get(),
        ]);
    }

    protected function getDefaultRole()
    {
        return Role::where('name', 'Employee')->first();
    }

    public function completeOnboarding(Request $request, User $user)
    {
        $validated = $request->validate([
            
            'employment_type_id' => 'required|exists:employment_types,id',
            'department_id'      => 'required|exists:departments,id',
            'position_id'        => 'required|exists:positions,id',
        ]);

        $employeeId = $this->generateEmployeeId(
            $validated['employment_type_id']
        );

        $position = Position::findOrFail($validated['position_id']);
        $department = Department::findOrFail($validated['department_id']);

        Personnel::create([
            'employee_id'        => $employeeId,
            'fullname'           => $user->name,
            'user_id'            => $user->id,

            'employment_type_id' => $validated['employment_type_id'],
            'department_id'      => $validated['department_id'],
            'position_id'        => $validated['position_id'],

            // Legacy fields
            'position'           => $position->position_name,
            'department'         => $department->department_name,

            'status'             => 'Active',
        ]);

        $defaultRole = $this->getDefaultRole();

        if (! $defaultRole) {
            return back()->with(
                'error',
                'Default Employee role was not found. Please contact the system administrator.'
            );
        }

        $user->update([
            'role_id' => $defaultRole->id,
            'status'  => 'approved',
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type'    => 'user_onboarding',
            'title'   => 'Onboarding Approved',
            'message' => 'Your employee onboarding has been completed and your account is now active.',
            'url'     => route('personnel.dashboard', [], false),
            'is_read' => 0,
        ]);

        ActivityLogger::log(
            'Users',
            'Completed Employee Onboarding',
            'Completed onboarding for '
                . $user->name
                . ' and assigned the default Employee system role.'
        );

        return redirect()
            ->route('admin.users.pending')
            ->with('success', 'Employee onboarded successfully.');
    }



    protected function generateEmployeeId($employmentTypeId)
    {
        $employmentType = EmploymentType::findOrFail($employmentTypeId);

        $prefix = $employmentType->employee_prefix;

        $lastEmployee = Personnel::where('employee_id', 'like', $prefix . '%')
            ->orderByDesc('employee_id')
            ->first();

        if (!$lastEmployee) {
            return $prefix . '001';
        }

        $lastNumber = (int) substr(
            $lastEmployee->employee_id,
            strlen($prefix)
        );

        return $prefix . str_pad(
            $lastNumber + 1,
            3,
            '0',
            STR_PAD_LEFT
        );
    }

    public function getEmployeeId($employmentTypeId)
    {
        return response()->json([
            'employee_id' => $this->generateEmployeeId($employmentTypeId)
        ]);
    }

    public function getPositions($employmentTypeId)
    {
        $employmentType = EmploymentType::findOrFail($employmentTypeId);

        return response()->json(
            $employmentType->positions()
                ->where('is_active', 1)
                ->orderBy('position_name')
                ->get([
                    'positions.id',
                    'positions.position_name'
                ])
                ->makeHidden('pivot')
        );
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'approved'
        ]);

        // Create Personnel record only if it doesn't already exist
        if (! Personnel::where('user_id', $user->id)->exists()) {

            Personnel::create([
                'employee_id' => 'EMP' . str_pad(
                    Personnel::max('id') + 1,
                    5,
                    '0',
                    STR_PAD_LEFT
                ),

                'fullname' => $user->name,

                'user_id' => $user->id,

                // Legacy fields (temporary)
                'position' => null,
                'department' => null,

                // New normalized fields
                'employment_type_id' => null,
                'department_id' => null,
                'position_id' => null,

                'status' => 'Active',
            ]);
        }

        ActivityLogger::log(
            'Users',
            'Approved User',
            'Approved user: ' . ($user->fullname ?? $user->name)
        );

        return back()->with('success', "{$user->name} has been approved.");
        }

        

    public function reject($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'rejected'
        ]);

        ActivityLogger::log(
            'Users',
            'Rejected User',
            'Rejected user: ' . ($user->fullname ?? $user->name)
        );

        return back()->with('success', "{$user->name} has been rejected.");
    }
}
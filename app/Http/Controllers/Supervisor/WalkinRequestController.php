<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Department;
use App\Models\WalkinRequest;
use App\Models\WalkinRequestItem;
use App\Models\DepartmentMaterial;
use App\Models\MaterialLog;
use App\Models\InventoryMovement;
use App\Models\Personnel;
use App\Models\EmploymentType;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class WalkinRequestController extends Controller
{
    public function create()
    {
        $materials = Material::orderBy('name')->get();

        $materialsForJs = $materials->map(fn ($m) => [
            'id' => $m->id,
            'name' => $m->name,
            'image_url' => $m->image_url,
            'stock' => $m->quantity,
        ])->values();

        $departments = Department::orderBy('department_name')->get();

        $employees = Personnel::orderBy('fullname')
            ->get(['id', 'fullname', 'employee_id', 'department_id']);

        $employmentTypes = EmploymentType::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view(
            'supervisor.walkin_requests.create',
            compact('materials', 'materialsForJs', 'departments', 'employees', 'employmentTypes')
        );
    }

    /**
     * Quick-add an employee from the Walk-In Issuance screen so a walk-in
     * requestor can be linked to a real Personnel record (and therefore
     * traceable) without leaving the page. Mirrors the fields captured by
     * the admin Employee Onboarding flow, but creates the User account
     * directly (approved, default "Employee" role) since there is no
     * self-registration to onboard here.
     */
    public function quickAddEmployee(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'employment_type_id' => 'required|exists:employment_types,id',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
        ]);

        $employmentType = EmploymentType::findOrFail($validated['employment_type_id']);
        $defaultRole = Role::defaultForEmploymentType($employmentType);

        if (!$defaultRole) {
            return response()->json([
                'message' => 'Default Employee role was not found. Please contact the system administrator.'
            ], 422);
        }

        $employeeId = Personnel::generateEmployeeId($validated['employment_type_id']);
        $position = Position::findOrFail($validated['position_id']);
        $department = Department::findOrFail($validated['department_id']);
        $temporaryPassword = Str::random(10);

        $personnel = DB::transaction(function () use ($validated, $defaultRole, $employeeId, $position, $department, $temporaryPassword) {

            $user = User::create([
                'name' => $validated['fullname'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($temporaryPassword),
                'must_change_password' => true,
                'role' => 'personnel',
                'role_id' => $defaultRole->id,
                'status' => 'approved',
            ]);

            return Personnel::create([
                'employee_id' => $employeeId,
                'fullname' => $validated['fullname'],
                'user_id' => $user->id,

                'employment_type_id' => $validated['employment_type_id'],
                'department_id' => $validated['department_id'],
                'position_id' => $validated['position_id'],

                'position' => $position->position_name,
                'department' => $department->department_name,

                'status' => 'Active',
            ]);
        });

        ActivityLogger::log(
            'Users',
            'Quick-Added Employee',
            'Created employee record for ' . $personnel->fullname . ' via Walk-In Issuance.'
        );

        return response()->json([
            'id' => $personnel->id,
            'fullname' => $personnel->fullname,
            'employee_id' => $personnel->employee_id,
            'department_id' => $personnel->department_id,
            'username' => $validated['username'],
            'temporary_password' => $temporaryPassword,
        ]);
    }

    // Same "quick-add without leaving the page" pattern as
    // quickAddEmployee() above — for when the destination department
    // genuinely isn't in the list yet.
    public function quickAddDepartment(Request $request)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:255|unique:departments,department_name',
            'department_code' => 'nullable|string|max:50',
        ]);

        $department = Department::create($validated);

        ActivityLogger::log(
            'Departments',
            'Quick-Added Department',
            'Created department "' . $department->department_name . '" via Walk-In Issuance.'
        );

        return response()->json([
            'id' => $department->id,
            'department_name' => $department->department_name,
        ]);
    }

    public function getEmployeeId($employmentTypeId)
    {
        return response()->json([
            'employee_id' => Personnel::generateEmployeeId($employmentTypeId)
        ]);
    }

    public function getPositions($employmentTypeId)
    {
        $employmentType = EmploymentType::findOrFail($employmentTypeId);

        return response()->json(
            $employmentType->positions()
                ->where('is_active', 1)
                ->orderByRaw('positions.sort_order IS NULL, positions.sort_order')
                ->orderBy('position_name')
                ->get([
                    'positions.id',
                    'positions.position_name'
                ])
                ->makeHidden('pivot')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'personnel_id' => 'required|exists:personnel,id',
            'department_id' => 'required',
            'material_id' => 'required|array',
            'material_id.*' => 'required|exists:materials,id',

            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',

            'room' => 'required',
            'purpose' => 'required',
        ]);

        $requestor = Personnel::findOrFail($request->personnel_id);

        foreach ($request->material_id as $index => $materialId)
        {
            $material = Material::findOrFail($materialId);

            $qty = $request->quantity[$index];

            if ($material->quantity < $qty)
            {
                return back()->with(
                    'error',
                    $material->name . ' has insufficient stock.'
                );
            }
        }

DB::transaction(function () use ($request, $requestor) {

        $walkin = WalkinRequest::create([
            'reference_no' => 'WI-' . now()->format('YmdHis'),
            'requestor_name' => $requestor->fullname,
            'personnel_id' => $requestor->id,
            'department_id' => $request->department_id,
            'room' => $request->room,
            'purpose' => $request->purpose,
            'issued_by' => auth()->id(),
            'issued_at' => now(),
        ]);

    
        foreach ($request->material_id as $index => $materialId)
        {

            $material = Material::findOrFail($materialId);
            $qty = $request->quantity[$index];

            $stockBefore = $material->quantity;
            $stockAfter = $stockBefore - $qty;

            WalkinRequestItem::create([
                'walkin_request_id' => $walkin->id,
                'material_id' => $materialId,
                'quantity' => $qty,
                'unit' => $material->unit->name,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
            ]);

            DepartmentMaterial::create([
                'department_id' => $request->department_id,
                'material_id' => $materialId,
                'quantity' => $qty,
                'request_id' => $walkin->id,
                'released_by' => auth()->id(),
                'released_at' => now(),
            ]);

            $material->decrement(
                'quantity',
                $qty
            );

            InventoryMovement::create([
                'material_id'    => $material->id,
                'movement_type'  => 'request',
                'quantity'       => $qty,
                'previous_stock' => $material->quantity + $qty,
                'new_stock'      => $material->quantity,
                'remarks'        => 'Walk-In Issue #' . $walkin->reference_no,
                'performed_by'   => auth()->id(),
            ]);

            MaterialLog::create([
                'material_id' => $material->id,
                'user_id' => auth()->id(),
                'action' => 'DEDUCTED',
                'quantity' => $qty,
                'remarks' => 'Walk-In Issue #' . $walkin->reference_no,
            ]);
        }
    }); 

            return redirect()
            ->route('walkin.create')
            ->with(
                'success',
                'Material issued successfully.'
        );
    }

    public function index()
    {
        return redirect()->route('walkin.history');
    }

    public function history()
    {
        $requests = WalkinRequest::with([
            'department',
            'items.material',
            'issuer',
            'personnel'
        ])
        ->latest()
        ->paginate(20);

        return view(
            'supervisor.walkin_requests.history',
            compact('requests')
        );
    }

    /**
     * All walk-in issuances tied to one employee, so an Inventory
     * Custodian can trace everything a specific person has requested
     * without needing HR's Employee Master permissions.
     */
    public function employeeHistory(Personnel $personnel)
    {
        $requests = $personnel->walkinRequests()
            ->with(['department', 'items.material', 'issuer'])
            ->latest()
            ->paginate(20);

        $totalIssuances = $personnel->walkinRequests()->count();

        $totalItemsIssued = WalkinRequestItem::whereIn(
            'walkin_request_id',
            $personnel->walkinRequests()->pluck('id')
        )->sum('quantity');

        return view(
            'supervisor.walkin_requests.employee_history',
            compact('personnel', 'requests', 'totalIssuances', 'totalItemsIssued')
        );
    }

    public function show($id)
    {
        $request = WalkinRequest::with([
            'department',
            'items.material',
            'issuer'
        ])->findOrFail($id);

        return view(
            'supervisor.walkin_requests.show',
            compact('request')
        );
    }

    public function print($id)
    {
        $request = WalkinRequest::with([
            'department',
            'items.material',
            'issuer'
        ])->findOrFail($id);

        return view(
            'supervisor.walkin_requests.print',
            compact('request')
        );
    }

}
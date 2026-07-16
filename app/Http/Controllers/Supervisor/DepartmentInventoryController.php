<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\DepartmentMaterial;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentInventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = DepartmentMaterial::with([
            'department',
            'material',
            'releaser'
        ]);

        if ($request->search) {
            $query->whereHas('material', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        $inventories = $query->latest()->get();

        $totalReleases = DepartmentMaterial::count();
        $totalQuantityReleased = DepartmentMaterial::sum('quantity');
        $departmentsInvolved = DepartmentMaterial::distinct('department_id')->count('department_id');
        $materialsInvolved = DepartmentMaterial::distinct('material_id')->count('material_id');

        $departments = Department::orderBy('department_name')->get();

        return view(
            'supervisor.department_inventory.index',
            compact(
                'inventories',
                'totalReleases',
                'totalQuantityReleased',
                'departmentsInvolved',
                'materialsInvolved',
                'departments'
            )
        );
    }

    
    /*
    |--------------------------------------------------------------------------
    | 📦 Department Inventory Balance
    |--------------------------------------------------------------------------
    */
    public function balance()
    {
        $balances = DepartmentMaterial::selectRaw('
                department_id,
                COUNT(DISTINCT material_id) as total_materials,
                SUM(quantity) as total_quantity
            ')
            ->with('department')
            ->groupBy('department_id')
            ->orderBy('department_id')
            ->get();

        return view(
            'supervisor.department_inventory.balance',
            compact('balances')
        );
    }

    public function details($departmentId)
    {
        $department = \App\Models\Department::findOrFail($departmentId);

        $materials = DepartmentMaterial::with('material')
            ->where('department_id', $departmentId)
            ->get();

        return view(
            'supervisor.department_inventory.details',
            compact(
                'department',
                'materials'
            )
        );
    }

}
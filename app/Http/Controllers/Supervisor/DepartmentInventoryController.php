<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\DepartmentMaterial;

class DepartmentInventoryController extends Controller
{
    public function index()
    {
        $inventories = DepartmentMaterial::with([
            'department',
            'material',
            'releaser'
        ])
        ->latest()
        ->get();

        return view(
            'supervisor.department_inventory.index',
            compact('inventories')
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
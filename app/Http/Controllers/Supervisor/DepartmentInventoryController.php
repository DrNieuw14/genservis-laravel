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
    | 📊 Department Inventory Summary
    |--------------------------------------------------------------------------
    */
    public function summary()
    {
        $summary = DepartmentMaterial::selectRaw('
                department_id,
                material_id,
                SUM(quantity) as total_quantity
            ')
            ->with([
                'department',
                'material'
            ])
            ->groupBy(
                'department_id',
                'material_id'
            )
            ->get();

        return view(
            'supervisor.department_inventory.summary',
            compact('summary')
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
                material_id,
                SUM(quantity) as total_quantity
            ')
            ->with([
                'department',
                'material'
            ])
            ->groupBy(
                'department_id',
                'material_id'
            )
            ->orderBy('department_id')
            ->get();

        return view(
            'supervisor.department_inventory.balance',
            compact('balances')
        );
    }

}
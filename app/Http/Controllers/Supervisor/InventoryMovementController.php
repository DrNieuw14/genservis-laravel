<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryMovement;

class InventoryMovementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INVENTORY MOVEMENT LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = InventoryMovement::with([
            'material.department',
            'user'
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->search) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('material', function ($material) use ($search) {

                    $material->where('name', 'LIKE', "%{$search}%");

                })

                ->orWhere('movement_type', 'LIKE', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | GET MOVEMENTS
        |--------------------------------------------------------------------------
        */

        $movements = $query->latest()->get();

        return view(
            'supervisor.inventory_movements.index',
            compact('movements')
        );
    }
}
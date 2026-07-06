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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class WalkinRequestController extends Controller
{
    public function create()
    {
        $materials = Material::orderBy('name')->get();

        $departments = Department::orderBy('department_name')->get();

        return view(
            'supervisor.walkin_requests.create',
            compact('materials', 'departments')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required',
            'department_id' => 'required',
            'material_id' => 'required|array',
            'material_id.*' => 'required|exists:materials,id',

            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',

            'room' => 'required',
            'purpose' => 'required',
        ]);

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

DB::transaction(function () use ($request) {
        
        $walkin = WalkinRequest::create([
            'reference_no' => 'WI-' . now()->format('YmdHis'),
            'requestor_name' => $request->employee_name,
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

    public function history()
    {
        $requests = WalkinRequest::with([
            'department',
            'items.material',
            'issuer'
        ])
        ->latest()
        ->paginate(20);

        return view(
            'supervisor.walkin_requests.history',
            compact('requests')
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

}
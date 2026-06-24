<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Department;
use App\Models\WalkinRequest;
use App\Http\Controllers\Supervisor\WalkinRequestController;
use App\Models\MaterialLog;
use Illuminate\Http\Request;


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
            'material_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'room' => 'required',
            'purpose' => 'required',
        ]);

        $material = Material::findOrFail(
            $request->material_id
        );

        if ($material->quantity < $request->quantity) {

            return back()->with(
                'error',
                'Insufficient stock.'
            );
        }

        $walkin = WalkinRequest::create([
            'reference_no' => 'WI-' . now()->format('YmdHis'),
            'requestor_name' => $request->employee_name,
            'department_id' => $request->department_id,
            'room' => $request->room,
            'purpose' => $request->purpose,
            'issued_by' => auth()->id(),
            'issued_at' => now(),
        ]);

        $stockBefore = $material->quantity;
        $stockAfter = $stockBefore - $request->quantity;

        WalkinRequestItem::create([
            'walkin_request_id' => $walkin->id,
            'material_id' => $request->material_id,
            'quantity' => $request->quantity,

            'unit' => $material->unit->name,

            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
        ]);
      
        $material->decrement(
            'quantity',
            $request->quantity
        );

        MaterialLog::create([
            'material_id' => $material->id,
            'user_id' => auth()->id(),
            'action' => 'DEDUCTED',
            'quantity' => $request->quantity,
            'remarks' => 'Walk-In Issue #' . $walkin->reference_no,
        ]);

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
}
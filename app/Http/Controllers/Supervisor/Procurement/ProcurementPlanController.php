<?php

namespace App\Http\Controllers\Supervisor\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementPlan;
use Illuminate\Http\Request;
use App\Services\DocumentNumberService;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;

class ProcurementPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProcurementPlan::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {

                $q->where('plan_number', 'like', '%' . $request->search . '%')
                  ->orWhere('year', 'like', '%' . $request->search . '%');

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where('status', $request->status);

        }

        /*
        |--------------------------------------------------------------------------
        | Latest First
        |--------------------------------------------------------------------------
        */

        $plans = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'supervisor.procurement.plans.index',
            compact('plans')
        );
    }

    public function create()
    {
        $departments = Department::orderBy('department_name')->get();
            

        $nextPPMPNumber = DocumentNumberService::generatePPMPNumber(
            date('Y') + 1
        );

        return view(
            'supervisor.procurement.plans.create',
            compact(
                'departments',
                'nextPPMPNumber'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|digits:4',
            'department_id' => 'required|exists:departments,id',
            'allocated_budget' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $plan = ProcurementPlan::create([
            'plan_number' => DocumentNumberService::generatePPMPNumber($request->year),
            'year' => $request->year,
            'department_id' => $request->department_id,
            'allocated_budget' => $request->allocated_budget,
            'approved_budget' => 0,
            'remaining_budget' => $request->allocated_budget,
            'status' => 'Draft',
            'prepared_by' => Auth::id(),
            'remarks' => $request->remarks,
        ]);

        return redirect()
            ->route('procurement.plans.show', $plan->id)
            ->with('success', 'Procurement Plan created successfully.');
    }

    public function show(string $id)
    {
        $plan = ProcurementPlan::with([
            'department',
            'items.unit'
        ])->findOrFail($id);

        $materials = \App\Models\Material::with([
            'category',
            'unit'
        ])
        ->orderBy('name')
        ->get();

        return view(
            'supervisor.procurement.plans.show',
            compact(
                'plan',
                'materials'
            )
        );
    }

    public function edit(string $id){}

    public function update(Request $request, string $id){}

    public function destroy(string $id){}
}
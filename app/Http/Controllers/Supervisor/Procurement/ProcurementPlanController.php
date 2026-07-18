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
        | Department Scoping
        |--------------------------------------------------------------------------
        | A user without view-ppmp (e.g. Department Chair / Unit Head) only sees
        | their own department's plans.
        */

        $user = Auth::user();

        $scopedDepartment = null;

        if (! $user->hasPermission('view-ppmp')) {

            // Personnel has both a department_id FK and a legacy plain-string
            // 'department' column, so ->department (property access) resolves to
            // the string column, not the department() relation. Look it up explicitly.
            $scopedDepartment = Department::find($user->personnel?->department_id);

            $query->where('department_id', $user->personnel?->department_id ?? 0);

        }

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
            compact('plans', 'scopedDepartment')
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
            ->route('procurement.plans.index')
            ->with('success', 'Procurement Plan created successfully.');
    }

    public function show(string $id)
    {
        $plan = ProcurementPlan::with([
            'department',
            'items.unit',
            'items.material.classification',
            'items.creator',
            'itemLogs' => fn ($query) => $query->with('performer')->latest(),
        ])->findOrFail($id);

        $user = Auth::user();

        if (! $user->hasPermission('view-ppmp') && $plan->department_id !== $user->personnel?->department_id) {

            abort(403);

        }

        $materials = \App\Models\Material::with([
            'category',
            'unit'
        ])
        ->orderBy('name')
        ->get();

        $categories = \App\Models\Category::all();

        $units = \App\Models\Unit::all();

        $classifications = \App\Models\ProcurementClassification::where('is_active', true)
            ->orderBy('main_category')
            ->orderBy('sub_category_c')
            ->get();

        return view(
            'supervisor.procurement.plans.show',
            compact(
                'plan',
                'materials',
                'categories',
                'units',
                'classifications'
            )
        );
    }

    public function edit(string $id)
    {
        $plan = ProcurementPlan::findOrFail($id);

        if ($plan->status !== 'Draft') {
            return redirect()
                ->route('procurement.plans.show', $plan->id)
                ->with('error', 'Only Draft plans can be edited.');
        }

        $departments = Department::orderBy('department_name')->get();

        return view(
            'supervisor.procurement.plans.edit',
            compact('plan', 'departments')
        );
    }

    public function update(Request $request, string $id)
    {
        $plan = ProcurementPlan::findOrFail($id);

        if ($plan->status !== 'Draft') {
            return redirect()
                ->route('procurement.plans.show', $plan->id)
                ->with('error', 'Only Draft plans can be edited.');
        }

        $request->validate([
            'year' => 'required|digits:4',
            'department_id' => 'required|exists:departments,id',
            'allocated_budget' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $plan->update([
            'year' => $request->year,
            'department_id' => $request->department_id,
            'allocated_budget' => $request->allocated_budget,
            'remaining_budget' => $request->allocated_budget - $plan->total_planned_cost,
            'remarks' => $request->remarks,
        ]);

        return redirect()
            ->route('procurement.plans.show', $plan->id)
            ->with('success', 'Procurement Plan updated successfully.');
    }

    public function destroy(string $id)
    {
        $plan = ProcurementPlan::findOrFail($id);

        if ($plan->status !== 'Draft') {
            return redirect()
                ->route('procurement.plans.show', $plan->id)
                ->with('error', 'Only Draft plans can be deleted.');
        }

        $plan->delete();

        return redirect()
            ->route('procurement.plans.index')
            ->with('success', 'Procurement Plan deleted successfully.');
    }

    public function submit(string $id)
    {
        $plan = ProcurementPlan::findOrFail($id);

        if ($plan->status !== 'Draft') {
            return redirect()
                ->route('procurement.plans.show', $plan->id)
                ->with('error', 'Only Draft plans can be submitted.');
        }

        if (! $plan->items()->exists()) {
            return redirect()
                ->route('procurement.plans.show', $plan->id)
                ->with('error', 'Add at least one procurement item before submitting.');
        }

        if ($plan->items()->where('is_approved', false)->exists()) {
            return redirect()
                ->route('procurement.plans.show', $plan->id)
                ->with('error', 'All items must be marked approved before this plan can be submitted.');
        }

        $plan->update([
            'status' => 'Submitted',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('procurement.plans.show', $plan->id)
            ->with('success', 'Procurement Plan submitted for approval.');
    }

    public function approve(string $id)
    {
        $plan = ProcurementPlan::findOrFail($id);

        if ($plan->status !== 'Submitted') {
            return redirect()
                ->route('procurement.plans.show', $plan->id)
                ->with('error', 'Only Submitted plans can be approved.');
        }

        $plan->update([
            'status' => 'Approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approved_budget' => $plan->allocated_budget,
        ]);

        return redirect()
            ->route('procurement.plans.show', $plan->id)
            ->with('success', 'Procurement Plan approved.');
    }

    public function reject(Request $request, string $id)
    {
        $plan = ProcurementPlan::findOrFail($id);

        if ($plan->status !== 'Submitted') {
            return redirect()
                ->route('procurement.plans.show', $plan->id)
                ->with('error', 'Only Submitted plans can be rejected.');
        }

        $request->validate([
            'reason' => 'nullable|string',
        ]);

        $remarks = $plan->remarks;

        if ($request->filled('reason')) {
            $remarks = trim($remarks . "\n\nRejected: " . $request->reason);
        }

        $plan->update([
            'status' => 'Rejected',
            'remarks' => $remarks,
        ]);

        return redirect()
            ->route('procurement.plans.show', $plan->id)
            ->with('success', 'Procurement Plan rejected.');
    }
}
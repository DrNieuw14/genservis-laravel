<?php

namespace App\Http\Controllers\Supervisor\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementPlan;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Services\DocumentNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseRequest::with(['plan', 'requestedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('pr_number', 'like', '%' . $request->search . '%');
        }

        $purchaseRequests = $query->latest()->paginate(15)->withQueryString();

        return view(
            'supervisor.procurement.purchase-requests.index',
            compact('purchaseRequests')
        );
    }

    public function create()
    {
        $plans = ProcurementPlan::orderByDesc('year')->get();

        $nextPrNumber = DocumentNumberService::generatePRNumber(date('Y'));

        return view(
            'supervisor.procurement.purchase-requests.create',
            compact('plans', 'nextPrNumber')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:procurement_plans,id',
            'pr_date' => 'required|date',
            'purpose' => 'nullable|string',
        ]);

        $pr = PurchaseRequest::create([
            'pr_number' => DocumentNumberService::generatePRNumber(date('Y', strtotime($validated['pr_date']))),
            'plan_id' => $validated['plan_id'],
            'pr_date' => $validated['pr_date'],
            'purpose' => $validated['purpose'] ?? null,
            'status' => 'Draft',
            'requested_by' => Auth::id(),
        ]);

        return redirect()
            ->route('procurement.purchase-requests.show', $pr->id)
            ->with('success', 'Purchase Request created. Add line items below.');
    }

    public function show(string $id)
    {
        $pr = PurchaseRequest::with([
            'plan',
            'requestedBy',
            'approvedBy',
            'items.planItem.unit',
            'items.planItem.material.classification',
        ])->findOrFail($id);

        $availableItems = $pr->plan->items()
            ->with(['unit', 'material.classification'])
            ->orderBy('material_name')
            ->get();

        return view(
            'supervisor.procurement.purchase-requests.show',
            compact('pr', 'availableItems')
        );
    }

    public function edit(string $id)
    {
        $pr = PurchaseRequest::findOrFail($id);

        if ($pr->status !== 'Draft') {
            return redirect()
                ->route('procurement.purchase-requests.show', $pr->id)
                ->with('error', 'Only Draft purchase requests can be edited.');
        }

        return view('supervisor.procurement.purchase-requests.edit', compact('pr'));
    }

    public function update(Request $request, string $id)
    {
        $pr = PurchaseRequest::findOrFail($id);

        if ($pr->status !== 'Draft') {
            return redirect()
                ->route('procurement.purchase-requests.show', $pr->id)
                ->with('error', 'Only Draft purchase requests can be edited.');
        }

        $validated = $request->validate([
            'pr_date' => 'required|date',
            'purpose' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $pr->update($validated);

        return redirect()
            ->route('procurement.purchase-requests.show', $pr->id)
            ->with('success', 'Purchase Request updated.');
    }

    public function destroy(string $id)
    {
        $pr = PurchaseRequest::findOrFail($id);

        if ($pr->status !== 'Draft') {
            return redirect()
                ->route('procurement.purchase-requests.show', $pr->id)
                ->with('error', 'Only Draft purchase requests can be deleted.');
        }

        $pr->delete();

        return redirect()
            ->route('procurement.purchase-requests.index')
            ->with('success', 'Purchase Request deleted.');
    }

    /**
     * Add a line item — draws from one of the parent plan's already-tagged
     * PPMP items, inheriting its ppa/UACS for utilization tracking.
     */
    public function addItem(Request $request, string $id)
    {
        $pr = PurchaseRequest::findOrFail($id);

        if ($pr->status !== 'Draft') {
            return back()->with('error', 'Only Draft purchase requests can have items added.');
        }

        $validated = $request->validate([
            'procurement_plan_item_id' => [
                'required',
                'exists:procurement_plan_items,id',
            ],
            'quantity_requested' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $item = new PurchaseRequestItem([
            'purchase_request_id' => $pr->id,
            'procurement_plan_item_id' => $validated['procurement_plan_item_id'],
            'quantity_requested' => $validated['quantity_requested'],
            'unit_cost' => $validated['unit_cost'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $item->calculateTotal();
        $item->save();

        return back()->with('success', 'Item added to Purchase Request.');
    }

    public function removeItem(string $id, string $itemId)
    {
        $pr = PurchaseRequest::findOrFail($id);

        if ($pr->status !== 'Draft') {
            return back()->with('error', 'Only Draft purchase requests can have items removed.');
        }

        PurchaseRequestItem::where('purchase_request_id', $pr->id)
            ->where('id', $itemId)
            ->delete();

        return back()->with('success', 'Item removed.');
    }

    public function approve(string $id)
    {
        $pr = PurchaseRequest::findOrFail($id);

        if ($pr->status !== 'Draft') {
            return back()->with('error', 'Only Draft purchase requests can be approved.');
        }

        if (! $pr->items()->exists()) {
            return back()->with('error', 'Add at least one item before approving.');
        }

        $pr->update([
            'status' => 'Approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Purchase Request approved — now counted as utilized against the PRE ceiling.');
    }

    public function reject(Request $request, string $id)
    {
        $pr = PurchaseRequest::findOrFail($id);

        if ($pr->status !== 'Draft') {
            return back()->with('error', 'Only Draft purchase requests can be rejected.');
        }

        $request->validate(['reason' => 'nullable|string']);

        $remarks = $pr->remarks;

        if ($request->filled('reason')) {
            $remarks = trim($remarks . "\n\nRejected: " . $request->reason);
        }

        $pr->update([
            'status' => 'Rejected',
            'remarks' => $remarks,
        ]);

        return back()->with('success', 'Purchase Request rejected.');
    }

    public function complete(string $id)
    {
        $pr = PurchaseRequest::findOrFail($id);

        if ($pr->status !== 'Approved') {
            return back()->with('error', 'Only Approved purchase requests can be marked completed.');
        }

        $pr->update(['status' => 'Completed']);

        return back()->with('success', 'Purchase Request marked completed.');
    }
}

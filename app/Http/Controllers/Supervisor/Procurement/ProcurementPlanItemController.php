<?php

namespace App\Http\Controllers\Supervisor\Procurement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Material;
use App\Models\Notification;
use App\Models\ProcurementPlan;
use App\Models\ProcurementPlanItem;
use App\Models\ProcurementPlanItemLog;

class ProcurementPlanItemController extends Controller
{
    /**
     * Block access to a plan that isn't the user's own department,
     * unless they have full (view-ppmp) access.
     */
    private function authorizePlanAccess(ProcurementPlan $plan): void
    {
        $user = Auth::user();

        if (! $user->hasPermission('view-ppmp') && $plan->department_id !== $user->personnel?->department_id) {

            abort(403);

        }
    }

    /**
     * Store a Procurement Item
     */
    public function store(Request $request, ProcurementPlan $plan)
    {
        $this->authorizePlanAccess($plan);

        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'material_id' => [
                'required',
                'exists:materials,id'
            ],

            'estimated_unit_cost' => [
                'required',
                'numeric',
                'min:0'
            ],

            'q1' => [
                'required',
                'integer',
                'min:0'
            ],

            'q2' => [
                'required',
                'integer',
                'min:0'
            ],

            'q3' => [
                'required',
                'integer',
                'min:0'
            ],

            'q4' => [
                'required',
                'integer',
                'min:0'
            ],

            'procurement_method' => [
                'required',
                'string',
                'in:COMPETITIVE BIDDING,SHOPPING,DIRECT CONTRACTING,NP- SMALL VALUE PROCUREMENT,NP- AGENCY TO AGENCY',
            ],

            'assign_classification_id' => [
                'nullable',
                'exists:procurement_classifications,id',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Material
        |--------------------------------------------------------------------------
        */

        $duplicate = ProcurementPlanItem::where('plan_id', $plan->id)
            ->where('material_id', $validated['material_id'])
            ->exists();

        if ($duplicate) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This material already exists in this Procurement Plan.'
                );

        }

        /*
        |--------------------------------------------------------------------------
        | Get Inventory Material
        |--------------------------------------------------------------------------
        */

        $material = Material::findOrFail(
            $validated['material_id']
        );

        if (! $material->classification_id && ! empty($validated['assign_classification_id'])) {

            $material->update([
                'classification_id' => $validated['assign_classification_id'],
            ]);

        }

        if (! $material->classification_id) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This material has no procurement classification assigned yet. Assign one via Materials Inventory before adding it to a PPMP.'
                );

        }

        $item = null;

        DB::transaction(function () use (
            $plan,
            $validated,
            $material,
            &$item
        ) {

            $item = new ProcurementPlanItem();

            $item->plan_id = $plan->id;

            $item->material_id = $material->id;

            $item->material_name = $material->name;

            $item->unit_id = $material->unit_id;

            $item->estimated_unit_cost =
                $validated['estimated_unit_cost'];

            $item->q1 = $validated['q1'];

            $item->q2 = $validated['q2'];

            $item->q3 = $validated['q3'];

            $item->q4 = $validated['q4'];

            $item->procurement_method = $validated['procurement_method'];

            $item->created_by = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Default Values
            |--------------------------------------------------------------------------
            */

            $item->priority = 'Medium';

            /*
            |--------------------------------------------------------------------------
            | Compute Totals
            |--------------------------------------------------------------------------
            */

            $item->calculateTotals();

            /*
            |--------------------------------------------------------------------------
            | Save
            |--------------------------------------------------------------------------
            */

            $item->save();

        });

        $this->notifyPlanPreparer(
            $plan,
            'New PPMP Item Added',
            Auth::user()->name . " added \"{$item->material_name}\" to PPMP-{$plan->plan_number}."
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'procurement.plans.show',
                $plan->id
            )
            ->with(
                'success',
                'Procurement Item added successfully.'
            );
    }

    public function show(ProcurementPlanItem $item)
    {
        $this->authorizePlanAccess($item->plan);

        return response()->json([
            'id' => $item->id,
            'material_id' => $item->material_id,
            'estimated_unit_cost' => $item->estimated_unit_cost,
            'q1' => $item->q1,
            'q2' => $item->q2,
            'q3' => $item->q3,
            'q4' => $item->q4,
            'priority' => $item->priority,
            'remarks' => $item->remarks,
            'procurement_method' => $item->procurement_method,
        ]);
    }

    /**
     * Update Procurement Item
     */
        public function update(
        Request $request,
        ProcurementPlanItem $item
    ) {
        $this->authorizePlanAccess($item->plan);

        $validated = $request->validate([

            'material_id' => [
                'required',
                'exists:materials,id'
            ],

            'estimated_unit_cost' => [
                'required',
                'numeric',
                'min:0'
            ],

            'q1' => [
                'required',
                'integer',
                'min:0'
            ],

            'q2' => [
                'required',
                'integer',
                'min:0'
            ],

            'q3' => [
                'required',
                'integer',
                'min:0'
            ],

            'q4' => [
                'required',
                'integer',
                'min:0'
            ],

            'priority' => [
                'nullable',
                'string'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

            'procurement_method' => [
                'required',
                'string',
                'in:COMPETITIVE BIDDING,SHOPPING,DIRECT CONTRACTING,NP- SMALL VALUE PROCUREMENT,NP- AGENCY TO AGENCY',
            ],

            'assign_classification_id' => [
                'nullable',
                'exists:procurement_classifications,id',
            ],

            'edit_reason' => [
                'nullable',
                'string',
            ],

        ]);

        $material = Material::findOrFail(
            $validated['material_id']
        );

        if (! $material->classification_id && ! empty($validated['assign_classification_id'])) {

            $material->update([
                'classification_id' => $validated['assign_classification_id'],
            ]);

        }

        if (! $material->classification_id) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This material has no procurement classification assigned yet. Assign one via Materials Inventory before adding it to a PPMP.'
                );

        }

        DB::transaction(function () use ($validated, $item, $material) {

            $item->material_id = $material->id;
            $item->material_name = $material->name;
            $item->unit_id = $material->unit_id;

            $item->estimated_unit_cost = $validated['estimated_unit_cost'];

            $item->q1 = $validated['q1'];
            $item->q2 = $validated['q2'];
            $item->q3 = $validated['q3'];
            $item->q4 = $validated['q4'];

            $item->procurement_method = $validated['procurement_method'];

            $item->priority =
                $validated['priority'] ?? 'Medium';

            $item->remarks =
                $validated['remarks'] ?? null;

            // Any edit invalidates a prior review - force re-approval.
            $item->is_approved = false;

            $item->calculateTotals();

            $item->save();
        });

        ProcurementPlanItemLog::create([
            'plan_id' => $item->plan_id,
            'material_name' => $item->material_name,
            'action' => 'edited',
            'reason' => $validated['edit_reason'] ?? null,
            'performed_by' => Auth::id(),
        ]);

        $this->notifyItemCreator(
            $item,
            'Procurement Item Updated',
            ! empty($validated['edit_reason'])
                ? "Your item \"{$item->material_name}\" was updated. Reason: {$validated['edit_reason']}"
                : "Your item \"{$item->material_name}\" was updated."
        );

        $this->notifyPlanPreparer(
            $item->plan,
            'PPMP Item Updated',
            Auth::user()->name . " edited \"{$item->material_name}\" in PPMP-{$item->plan->plan_number}."
        );

        return back()->with(
            'success',
            'Procurement Item updated successfully.'
        );
    }

    /**
     * Delete Procurement Item
     */
    public function destroy(Request $request, ProcurementPlanItem $item)
    {
        $plan = $item->plan;

        $this->authorizePlanAccess($plan);

        $validated = $request->validate([
            'reason' => ['required', 'string'],
        ]);

        ProcurementPlanItemLog::create([
            'plan_id' => $plan->id,
            'material_name' => $item->material_name,
            'action' => 'deleted',
            'reason' => $validated['reason'],
            'performed_by' => Auth::id(),
        ]);

        $this->notifyItemCreator(
            $item,
            'Procurement Item Removed',
            "Your item \"{$item->material_name}\" was removed from PPMP-{$plan->plan_number}. Reason: {$validated['reason']}"
        );

        $planId = $item->plan_id;

        $item->delete();

        return redirect()
            ->route('procurement.plans.show', $planId)
            ->with(
                'success',
                'Procurement Item deleted successfully.'
            );
    }

    /**
     * Toggle an item's "approved / no revision needed" checkpoint.
     * Route-gated to view-ppmp (Secretary/Procurement Officer/Administrator) -
     * a Department Chair cannot approve her own items.
     */
    public function toggleApproval(ProcurementPlanItem $item)
    {
        $item->update([
            'is_approved' => ! $item->is_approved,
        ]);

        return back()->with(
            'success',
            $item->is_approved
                ? "\"{$item->material_name}\" marked as approved."
                : "\"{$item->material_name}\" marked as pending review."
        );
    }

    /**
     * Notify the user who originally added this item, unless they're the
     * one making the change themselves.
     */
    private function notifyItemCreator(ProcurementPlanItem $item, string $title, string $message): void
    {
        if (! $item->created_by || $item->created_by === Auth::id()) {

            return;

        }

        Notification::create([
            'user_id' => $item->created_by,
            'type' => 'procurement_item',
            'title' => $title,
            'message' => $message,
            'url' => route('procurement.plans.show', $item->plan_id, false),
            'is_read' => 0,
        ]);
    }

    /**
     * Notify whoever prepared the plan (e.g. the Secretary who created it),
     * unless they're the one making the change themselves.
     */
    private function notifyPlanPreparer(ProcurementPlan $plan, string $title, string $message): void
    {
        if (! $plan->prepared_by || $plan->prepared_by === Auth::id()) {

            return;

        }

        Notification::create([
            'user_id' => $plan->prepared_by,
            'type' => 'procurement_item',
            'title' => $title,
            'message' => $message,
            'url' => route('procurement.plans.show', $plan->id, false),
            'is_read' => 0,
        ]);
    }
}
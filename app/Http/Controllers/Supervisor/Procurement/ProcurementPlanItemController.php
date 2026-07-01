<?php

namespace App\Http\Controllers\Supervisor\Procurement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Material;
use App\Models\ProcurementPlan;
use App\Models\ProcurementPlanItem;

class ProcurementPlanItemController extends Controller
{
    /**
     * Store a Procurement Item
     */
    public function store(Request $request, ProcurementPlan $plan)
    {
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

        DB::transaction(function () use (
            $plan,
            $validated,
            $material
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

    /**
     * Update Procurement Item
     */
    public function update(
        Request $request,
        ProcurementPlanItem $item
    ) {
        //
    }

    /**
     * Delete Procurement Item
     */
    public function destroy(
        ProcurementPlanItem $item
    ) {
        //
    }
}
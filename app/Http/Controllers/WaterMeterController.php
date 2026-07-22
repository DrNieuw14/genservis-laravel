<?php

namespace App\Http\Controllers;

use App\Models\WaterMeter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaterMeterController extends Controller
{
    public function index()
    {
        $meters = WaterMeter::withCount('bills')->orderBy('label')->get();

        return view('water_bills.meters', compact('meters'));
    }

    public function show(WaterMeter $meter)
    {
        $meter->load('bills');

        return view('water_bills.meter_show', compact('meter'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateMeter($request);
        $validated['created_by'] = Auth::id();

        WaterMeter::create($validated);

        return back()->with('success', 'Water meter added.');
    }

    public function update(Request $request, WaterMeter $meter)
    {
        $validated = $this->validateMeter($request);
        $validated['is_active'] = $request->boolean('is_active');

        $meter->update($validated);

        return back()->with('success', 'Water meter updated.');
    }

    public function destroy(WaterMeter $meter)
    {
        if ($meter->bills()->exists()) {
            return back()->with('error', 'This meter has recorded bills and cannot be deleted. Mark it inactive instead.');
        }

        $meter->delete();

        return back()->with('success', 'Water meter removed.');
    }

    private function validateMeter(Request $request): array
    {
        return $request->validate([
            'label' => 'required|string|max:255',
            'account_no' => 'nullable|string|max:255',
            'service_no' => 'nullable|string|max:255',
            'meter_no' => 'nullable|string|max:255',
            'meter_brand' => 'nullable|string|max:255',
        ]);
    }
}

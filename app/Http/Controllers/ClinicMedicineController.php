<?php

namespace App\Http\Controllers;

use App\Models\ClinicMedicine;
use Illuminate\Http\Request;

class ClinicMedicineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $medicines = ClinicMedicine::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('clinic_medicines.index', compact('medicines', 'search'));
    }

    public function create()
    {
        return view('clinic_medicines.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        ClinicMedicine::create($validated);

        return redirect()
            ->route('clinic-medicines.index')
            ->with('success', 'Medicine added to inventory.');
    }

    public function edit($id)
    {
        $medicine = ClinicMedicine::findOrFail($id);

        return view('clinic_medicines.edit', compact('medicine'));
    }

    public function update(Request $request, $id)
    {
        $medicine = ClinicMedicine::findOrFail($id);

        $validated = $this->validated($request);

        $medicine->update($validated);

        return redirect()
            ->route('clinic-medicines.index')
            ->with('success', 'Medicine updated.');
    }

    public function destroy($id)
    {
        $medicine = ClinicMedicine::findOrFail($id);
        $medicine->delete();

        return redirect()
            ->route('clinic-medicines.index')
            ->with('success', 'Medicine removed from inventory.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:150',
            'brand' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'current_stock' => 'required|integer|min:0',
            'quantity_received' => 'nullable|integer|min:0',
            'expiration_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Please enter the medicine name.',
            'current_stock.required' => 'Please enter the current stock count.',
        ]);
    }
}

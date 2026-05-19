<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Unit;

class UnitController extends Controller
{
    // 📋 List Units
    public function index()
    {
        $units = Unit::withCount('materials')
            ->latest()
            ->get();

        return view('supervisor.units.index', compact('units'));
    }

    // ➕ Create Form
    public function create()
    {
        return view('supervisor.units.create');
    }

    // 💾 Store Unit
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:units,name'
        ]);

        Unit::create([
            'name' => $request->name
        ]);

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit added successfully!');
    }

    // ✏️ Edit Form
    public function edit($id)
    {
        $unit = Unit::findOrFail($id);

        return view('supervisor.units.edit', compact('unit'));
    }

    // 🔄 Update Unit
    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:units,name,' . $unit->id
        ]);

        $unit->update([
            'name' => $request->name
        ]);

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit updated successfully!');
    }

    // 🗑 Delete Unit
    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);

        // ❌ Prevent delete if used
        if ($unit->materials()->count() > 0) {

            return back()->with(
                'error',
                'Cannot delete unit with existing materials.'
            );
        }

        $unit->delete();

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit deleted successfully!');
    }
}
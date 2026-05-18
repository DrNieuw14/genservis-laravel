<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    // 📋 List Materials
    public function index(Request $request)
    {
        $query = Material::with(['category', 'unit']);

        // 🔍 Search
        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $materials = $query->latest()->get();

        // 📊 Statistics
        $totalMaterials = Material::count();

        $lowStock = Material::whereColumn('quantity', '<=', 'threshold')
            ->where('quantity', '>', 0)
            ->count();

        $outOfStock = Material::where('quantity', '<=', 0)->count();

        return view('supervisor.materials.index', compact(
            'materials',
            'totalMaterials',
            'lowStock',
            'outOfStock'
        ));
    }

    // ➕ Show Create Form
    public function create()
    {
        $categories = Category::all();
        $units = Unit::all();

        return view('supervisor.materials.create', compact('categories', 'units'));
    }

    // 💾 Store Material
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'quantity' => 'required|integer|min:0',
        ]);

        Material::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'quantity' => $request->quantity,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('materials.index')
                         ->with('success', 'Material added successfully');
    }

    // ✏️ Edit Material Form
    public function edit($id)
    {
        $material = Material::findOrFail($id);

        $categories = Category::all();
        $units = Unit::all();

        return view('supervisor.materials.edit', compact(
            'material',
            'categories',
            'units'
        ));
    }

    // 💾 Update Material
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'quantity' => 'required|integer|min:0',
            'threshold' => 'required|integer|min:0',
        ]);

        $material = Material::findOrFail($id);

        $material->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'quantity' => $request->quantity,
            'threshold' => $request->threshold,
        ]);

        return redirect()
            ->route('materials.index')
            ->with('success', 'Material updated successfully!');
    }

    // 🗑 Delete Material
    public function destroy($id)
    {
        $material = Material::findOrFail($id);

        $material->delete();

        return redirect()
            ->route('materials.index')
            ->with('success', 'Material deleted successfully!');
    }

}
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
    public function index()
    {
        $materials = Material::with(['category', 'unit'])->get();

        return view('supervisor.materials.index', compact('materials'));
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
}
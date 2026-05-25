<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Category;
use App\Models\Unit;
use App\Models\MaterialLog;
use App\Models\MaterialRestockLog;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;


class MaterialController extends Controller
{

    



    // 📋 List Materials
    public function index(Request $request)
    {
        $query = Material::with([
            'category',
            'unit',
            'creator'
        ]);

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

        $departments = Department::all();

        return view(
            'supervisor.materials.create',
            compact(
                'categories',
                'units',
                'departments'
            )
        );
    }

    // 💾 Store Material
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'department_id' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'quantity' => 'required|integer|min:0',
        ]);

        $material = Material::create([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'quantity' => $request->quantity,
            'created_by' => Auth::id(),
        ]);

        // 📜 Create Inventory Log
        MaterialLog::create([
            'material_id' => $material->id,
            'user_id' => Auth::id(),
            'action' => 'stock_in',
            'quantity' => $request->quantity,
            'remarks' => 'Initial stock added',
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

        $departments = Department::all();

        return view('supervisor.materials.edit', compact(
            'material',
            'categories',
            'units',
            'departments'
        ));
    }

    // 💾 Update Material
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'department_id' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'quantity' => 'required|integer|min:0',
            'threshold' => 'required|integer|min:0',
        ]);

        $material = Material::findOrFail($id);

        $material->update([
            'name' => $request->name,
            'department_id' => $request->department_id,
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

    // 📜 Material Logs
    public function logs(Request $request)
    {
        $query = \App\Models\MaterialLog::with([
            'material',
            'user'
        ]);

        // 🔍 SEARCH
        if ($request->search) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('material', function ($material) use ($search) {
                    $material->where('name', 'LIKE', "%{$search}%");
                })

                ->orWhereHas('user', function ($user) use ($search) {
                    $user->where('username', 'LIKE', "%{$search}%");
                })

                ->orWhere('action', 'LIKE', "%{$search}%");

            });
        }

        // 📅 DATE FILTER
        if ($request->date_filter) {

            if ($request->date_filter == 'today') {

                $query->whereDate('created_at', today());

            } elseif ($request->date_filter == 'week') {

                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);

            } elseif ($request->date_filter == 'month') {

                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            }
        }

        $logs = $query->latest()->get();

        return view('supervisor.materials.logs', compact('logs'));
    }

        /*
|--------------------------------------------------------------------------
| RESTOCK FORM
|--------------------------------------------------------------------------
*/

public function restockForm($id)
{
    $material = Material::findOrFail($id);

    return view(
        'supervisor.materials.restock',
        compact('material')
    );
}

/*
|--------------------------------------------------------------------------
| RESTOCK MATERIAL
|--------------------------------------------------------------------------
*/

public function restock(Request $request, $id)
{
    $request->validate([

        'added_stock' => 'required|integer|min:1',

        'supplier' => 'nullable|string|max:255',

        'remarks' => 'nullable|string',

    ]);

    $material = Material::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | OLD STOCK
    |--------------------------------------------------------------------------
    */

    $oldStock = $material->quantity;

    /*
    |--------------------------------------------------------------------------
    | ADD STOCK
    |--------------------------------------------------------------------------
    */

    $material->quantity += $request->added_stock;

    $material->save();

    /*
    |--------------------------------------------------------------------------
    | SAVE RESTOCK LOG
    |--------------------------------------------------------------------------
    */

    MaterialRestockLog::create([

        'material_id'    => $material->id,

        'previous_stock' => $oldStock,

        'added_stock'    => $request->added_stock,

        'new_stock'      => $material->quantity,

        'supplier'       => $request->supplier,

        'remarks'        => $request->remarks,

        'restocked_by'   => auth()->id(),

    ]);

    /*
    |--------------------------------------------------------------------------
    | OPTIONAL GENERAL MATERIAL LOG
    |--------------------------------------------------------------------------
    */

    MaterialLog::create([

        'material_id' => $material->id,

        'user_id' => Auth::id(),

        'action' => 'restock',

        'quantity' => $request->added_stock,

        'remarks' => 'Material restocked',

    ]);

    return redirect()
        ->route('materials.index')
        ->with('success', 'Material restocked successfully!');
}

    

}
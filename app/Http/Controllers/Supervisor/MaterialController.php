<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Category;
use App\Models\Unit;
use App\Models\MaterialLog;
use App\Models\MaterialRestockLog;
use App\Models\MaterialRequestItem;
use App\Models\InventoryMovement;
use App\Models\Department;
use App\Models\DepartmentMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;  
use App\Imports\MaterialImport;
use Maatwebsite\Excel\Facades\Excel;


class MaterialController extends Controller
{

    // 📋 List Materials
    public function index(Request $request)
    {

        $expiringSoon = MaterialRestockLog::where('has_expiration', 1)
            ->where('quantity_remaining', '>', 0)
            ->whereDate('expiration_date', '<=', now()->addDays(30))
            ->whereDate('expiration_date', '>=', now())
            ->count();

        $expiredItems = MaterialRestockLog::where('has_expiration', 1)
            ->where('quantity_remaining', '>', 0)
            ->whereDate('expiration_date', '<', now())
            ->count();

        $query = Material::with([
            'category',
            'unit',
            'department',
            'creator'
        ]);

        
        // 🔍 Search
        if ($request->search) {

            $query->where(
                'name',
                'LIKE',
                '%' . $request->search . '%'
            );

        }

        // 🏢 Department Filter
        if ($request->department_id) {

            $query->where(
                'department_id',
                $request->department_id
            );

        }

        // 📦 Category Filter
        if ($request->category_id) {

            $query->where(
                'category_id',
                $request->category_id
            );

        }
        
        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        $status = $request->status;

        if ($status == 'critical') {

            $query->where('quantity', '>', 0)
                ->where('quantity', '<=', 5);

        }

        elseif ($status == 'low') {

            $query->where('quantity', '>', 5)
                ->whereColumn('quantity', '<=', 'threshold');

        }

        elseif ($status == 'out') {

            $query->where('quantity', '<=', 0);

        }

        elseif ($status == 'expiring') {

            $query->whereHas('restockLogs', function ($q) {

                $q->where('has_expiration', 1)
                ->where('quantity_remaining', '>', 0)
                ->whereDate('expiration_date', '<=', now()->addDays(30))
                ->whereDate('expiration_date', '>=', now());

            });

        }

        elseif ($status == 'expired') {

            $query->whereHas('restockLogs', function ($q) {

                $q->where('has_expiration', 1)
                ->where('quantity_remaining', '>', 0)
                ->whereDate('expiration_date', '<', now());

            });

        }

        $materials = $query->latest()->get();

        // 📊 Statistics
        $totalMaterials = Material::count();

        $criticalStock = Material::where('quantity', '>', 0)
            ->where('quantity', '<=', 5)
            ->count();

        $lowStock = Material::where('quantity', '>', 5)
            ->whereColumn('quantity', '<=', 'threshold')
            ->count();

        $outOfStock = Material::where('quantity', '<=', 0)->count();

        
        $departments = Department::all();

        $categories = Category::all();

        $categorySummary = Category::withCount('materials')
        ->orderBy('materials_count', 'desc')
        ->get();
        
        return view('supervisor.materials.index', compact(
            'materials',
            'departments',
            'categories',
            'categorySummary',
            'totalMaterials',
            'lowStock',
            'criticalStock',
            'outOfStock',
            'expiringSoon',
            'expiredItems'
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
            'department_id' => 'required|exists:departments,id',
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

        // 📦 SAVE INVENTORY MOVEMENT
        InventoryMovement::create([

            'material_id' => $material->id,

            'movement_type' => 'initial_stock',

            'quantity' => $request->quantity,

            'previous_stock' => 0,

            'new_stock' => $request->quantity,

            'remarks' => 'Initial stock added',

            'performed_by' => auth()->id(),

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

        /*
        |--------------------------------------------------------------------------
        | GENERATE BATCH NUMBER
        |--------------------------------------------------------------------------
        */

        $lastBatch = MaterialRestockLog::max('id') + 1;

        $batchNo = 'RST-' .
                    date('Y') .
                    '-' .
                    str_pad($lastBatch, 4, '0', STR_PAD_LEFT);

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
            'department_id' => 'required|exists:departments,id',
            'category_id' => 'required',
            'unit_id' => 'required',
            
            'threshold' => 'required|integer|min:0',
        ]);

        $material = Material::findOrFail($id);

        $material->update([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            
            'threshold' => $request->threshold,
        ]);

        return redirect()
            ->route('materials.index')
            ->with('success', 'Material updated successfully!');
    }

    // 🗑 Delete Material
    

    public function destroy(Material $material)
    {
        if (
            DepartmentMaterial::where(
                'material_id',
                $material->id
            )->exists()
        ) {
            return back()->with(
                'error',
                'Cannot delete material because it is already used in department inventory.'
            );
        }

        $material->delete();

        return back()->with(
            'success',
            'Material deleted successfully.'
        );
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
    | INVENTORY MOVEMENTS
    |--------------------------------------------------------------------------
    */

    public function movements(Request $request)
    {
        $query = InventoryMovement::with([
            'material',
            'user'
        ]);

        // 🔍 Search
        if ($request->search) {

            $search = $request->search;

            $query->whereHas('material', function ($q) use ($search) {

                $q->where(
                    'name',
                    'LIKE',
                    "%{$search}%"
                );

            });

        }

        $movements = $query->latest()->get();

        return view(
            'supervisor.materials.movements',
            compact('movements')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW MATERIAL DETAILS
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $material = Material::with([
            'category',
            'unit',
            'department',
            'creator'
        ])->findOrFail($id);

        // 📦 Recent Inventory Movements
        $movements = InventoryMovement::where(
                'material_id',
                $material->id
            )
            ->latest()
            ->take(10)
            ->get();

        // 📜 Restock Logs
        $restocks = MaterialRestockLog::where(
                'material_id',
                $material->id
            )
            ->latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT MATERIAL DISTRIBUTION
        |--------------------------------------------------------------------------
        */

        $distributions = MaterialRequestItem::with([
                'request.user',
                'request.department'
            ])
            ->where('material_id', $material->id)
            ->latest()
            ->take(10)
            ->get();

        return view(
            'supervisor.materials.show',
            compact(
                'material',
                'movements',
                'restocks',
                'distributions'
            )
        );
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

            'invoice_no' => 'nullable|string|max:255',

            'has_expiration' => 'required|boolean',

            'expiration_date' => 'nullable|date|after:today',

            'remarks' => 'nullable|string',

        ]);

        $material = Material::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | GENERATE BATCH NUMBER
        |--------------------------------------------------------------------------
        */

        $lastBatchId = MaterialRestockLog::max('id') ?? 0;

        $batchNo = 'RST-' .
                    date('Y') .
                    '-' .
                    str_pad(
                        $lastBatchId + 1,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );

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

            'material_id' => $material->id,

            'batch_no' => $batchNo,

            'previous_stock' => $oldStock,

            'added_stock' => $request->added_stock,

            'quantity_remaining' => $request->added_stock,

            'new_stock' => $material->quantity,

            'supplier' => $request->supplier,

            'invoice_no' => $request->invoice_no,

            'has_expiration' => $request->has_expiration,

            'expiration_date' =>
                $request->has_expiration
                    ? $request->expiration_date
                    : null,

            'remarks' => $request->remarks,

            'restocked_by' => auth()->id(),

        ]);

        /*
        |--------------------------------------------------------------------------
        | SAVE INVENTORY MOVEMENT
        |--------------------------------------------------------------------------
        */

        InventoryMovement::create([

            'material_id' => $material->id,

            'movement_type' => 'restock',

            'quantity' => $request->added_stock,

            'previous_stock' => $oldStock,

            'new_stock' => $material->quantity,

            'remarks' => $request->remarks,

            'performed_by' => auth()->id(),

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
        ->with(
            'success',
            'Material restocked successfully!'
        );
    } 

    /*
    |--------------------------------------------------------------------------
    | IMPORT FORM
    |--------------------------------------------------------------------------
    */

    public function importForm()
    {
        return view('supervisor.materials.import');
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT STORE
    |--------------------------------------------------------------------------
    */

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(
            new MaterialImport,
            $request->file('file')
        );

        return redirect()
            ->route('materials.index')
            ->with(
                'success',
                'Inventory imported successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | INVENTORY SUMMARY REPORT
    |--------------------------------------------------------------------------
    */

    public function inventorySummary()
    {
        $totalMaterials = Material::count();

        $criticalStock = Material::where('quantity', '>', 0)
            ->where('quantity', '<=', 5)
            ->count();

        $lowStock = Material::where('quantity', '>', 5)
            ->whereColumn('quantity', '<=', 'threshold')
            ->count();

        $outOfStock = Material::where('quantity', '<=', 0)
            ->count();

        $availableMaterials = Material::where('quantity', '>', 0)
            ->where('quantity', '>', DB::raw('threshold'))
            ->count(); 
            
            $healthyMaterials = $availableMaterials;

            $inventoryHealth = $totalMaterials > 0
                ? round(($healthyMaterials / $totalMaterials) * 100)
                : 0;

            if ($inventoryHealth >= 90) {
                $healthStatus = 'Excellent';
            } elseif ($inventoryHealth >= 75) {
                $healthStatus = 'Good';
            } elseif ($inventoryHealth >= 50) {
                $healthStatus = 'Fair';
            } else {
                $healthStatus = 'Poor';
            }

        $expiringSoon = MaterialRestockLog::where('has_expiration', 1)
            ->where('quantity_remaining', '>', 0)
            ->whereDate('expiration_date', '<=', now()->addDays(30))
            ->whereDate('expiration_date', '>=', now())
            ->count();

        $expiredItems = MaterialRestockLog::where('has_expiration', 1)
            ->where('quantity_remaining', '>', 0)
            ->whereDate('expiration_date', '<', now())
            ->count();

        /*
        |--------------------------------------------------------------------------
        | DETAILED REPORT DATA
        |--------------------------------------------------------------------------
        */

        $criticalMaterials = Material::with('department')
            ->where('quantity', '>', 0)
            ->where('quantity', '<=', 5)
            ->orderBy('quantity')
            ->get();

        $lowStockMaterials = Material::with('department')
            ->where('quantity', '>', 5)
            ->whereColumn('quantity', '<=', 'threshold')
            ->orderBy('quantity')
            ->get();

        $outOfStockMaterials = Material::with('department')
            ->where('quantity', '<=', 0)
            ->orderBy('name')
            ->get();

        $expiringMaterials = MaterialRestockLog::with([
            'material.department'
        ])
        ->where('has_expiration', 1)
        ->where('quantity_remaining', '>', 0)
        ->whereDate('expiration_date', '<=', now()->addDays(30))
        ->whereDate('expiration_date', '>=', now())
        ->orderBy('expiration_date')
        ->get();

        $expiredMaterials = MaterialRestockLog::with([
            'material.department'
        ])
        ->where('has_expiration', 1)
        ->where('quantity_remaining', '>', 0)
        ->whereDate('expiration_date', '<', now())
        ->orderBy('expiration_date')
        ->get();

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT INVENTORY SUMMARY
        |--------------------------------------------------------------------------
        */

        $departmentSummary = Department::with('materials')->get();

        $departmentSummary = Department::select(
                'departments.id',
                'departments.department_name'
            )
            ->leftJoin(
                'department_materials',
                'departments.id',
                '=',
                'department_materials.department_id'
            )
            ->selectRaw('
                COUNT(DISTINCT department_materials.material_id) as total_materials,
                COALESCE(SUM(department_materials.quantity),0) as total_quantity,
                MAX(department_materials.released_at) as last_release
            ')
            ->groupBy(
                'departments.id',
                'departments.department_name'
            )
            ->orderBy('departments.department_name')
            ->get();

        return view(
            'supervisor.reports.inventory_summary',
        compact(
            'totalMaterials',
            'availableMaterials',
            'inventoryHealth',
            'healthStatus',
            'criticalStock',
            'lowStock',
            'outOfStock',
            'expiringSoon',
            'expiredItems',
            'criticalMaterials',
            'lowStockMaterials',
            'outOfStockMaterials',
            'expiringMaterials',
            'expiredMaterials',
            'departmentSummary',
        )
        );
    }

    public function executiveSummary()
    {
        $totalMaterials = Material::count();

        $criticalStock = Material::where('quantity', '>', 0)
            ->where('quantity', '<=', 5)
            ->count();

        $lowStock = Material::where('quantity', '>', 5)
            ->whereColumn('quantity', '<=', 'threshold')
            ->count();

        $outOfStock = Material::where('quantity', '<=', 0)
            ->count();

        $availableMaterials = Material::where('quantity', '>', 0)
            ->where('quantity', '>', DB::raw('threshold'))
            ->count();

        $expiringSoon = MaterialRestockLog::where('has_expiration',1)
            ->where('quantity_remaining','>',0)
            ->whereDate('expiration_date','<=',now()->addDays(30))
            ->whereDate('expiration_date','>=',now())
            ->count();

        $inventoryHealth = $totalMaterials > 0
            ? round(($availableMaterials / $totalMaterials) * 100)
            : 0;

        if ($inventoryHealth >= 90) {
            $healthStatus = 'Excellent';
        } elseif ($inventoryHealth >= 75) {
            $healthStatus = 'Good';
        } elseif ($inventoryHealth >= 50) {
            $healthStatus = 'Fair';
        } else {
            $healthStatus = 'Poor';
        }

        $topCritical = Material::with('department')
            ->where('quantity','>',0)
            ->where('quantity','<=',5)
            ->orderBy('quantity')
            ->take(10)
            ->get();

        return view(
            'supervisor.reports.executive_summary',
            compact(
                'totalMaterials',
                'availableMaterials',
                'criticalStock',
                'lowStock',
                'outOfStock',
                'expiringSoon',
                'inventoryHealth',
                'healthStatus',
                'topCritical'
            )
        );
    }

}
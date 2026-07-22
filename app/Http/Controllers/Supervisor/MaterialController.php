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
use App\Models\WalkinRequestItem;
use App\Models\ProcurementClassification;
use App\Models\ProcurementPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Imports\MaterialImport;
use Maatwebsite\Excel\Facades\Excel;


class MaterialController extends Controller
{
    /**
     * "Critical" is only meaningful relative to an item's own reorder point —
     * a single durable tool (threshold 8) isn't critical the way a nearly-out
     * consumable (threshold 15) is. This is the fraction of an item's
     * threshold at/below which it counts as critical instead of merely low.
     */
    private const CRITICAL_THRESHOLD_RATIO = 0.3;

    /**
     * Every material, eager-loaded with department/category/unit, annotated
     * with a `stock_status` (out_of_stock/critical/low/available) computed
     * relative to each item's own threshold — the single source of truth
     * every inventory report should classify materials against, instead of
     * each report re-deriving its own (and drifting out of sync).
     */
    private function allMaterialsWithStatus()
    {
        return Material::with(['department', 'category', 'unit'])
            ->get()
            ->map(function ($material) {
                $criticalCutoff = (int) ceil($material->threshold * self::CRITICAL_THRESHOLD_RATIO);

                $material->stock_status = match (true) {
                    $material->quantity <= 0 => 'out_of_stock',
                    $material->quantity <= $criticalCutoff => 'critical',
                    $material->quantity <= $material->threshold => 'low',
                    default => 'available',
                };

                return $material;
            })
            ->sortBy([
                fn ($m) => $m->category->name ?? '',
                fn ($m) => $m->name,
            ])
            ->values();
    }

    /**
     * Bucket every material into out-of-stock / critical / low / available,
     * using each item's own threshold rather than a flat quantity cutoff.
     */
    private function inventoryStockBuckets(): array
    {
        $counts = $this->allMaterialsWithStatus()->countBy('stock_status');

        return [
            'outOfStock' => $counts->get('out_of_stock', 0),
            'criticalStock' => $counts->get('critical', 0),
            'lowStock' => $counts->get('low', 0),
            'availableMaterials' => $counts->get('available', 0),
        ];
    }

    /**
     * The materials furthest below their own threshold, not just the ones
     * with the lowest raw quantity — otherwise naturally single-unit durable
     * items (a jigsaw, a chess timer) crowd out genuinely low consumables.
     */
    private function topCriticalMaterials(?int $limit = 10)
    {
        $critical = $this->allMaterialsWithStatus()
            ->where('stock_status', 'critical')
            ->sortBy(fn ($m) => $m->threshold > 0 ? $m->quantity / $m->threshold : 0);

        if ($limit !== null) {
            $critical = $critical->take($limit);
        }

        return $critical->values();
    }

    /**
     * Materials below their reorder threshold but not yet critical, closest
     * to the danger zone first.
     */
    private function allLowStockMaterials()
    {
        return $this->allMaterialsWithStatus()
            ->where('stock_status', 'low')
            ->sortBy(fn ($m) => $m->threshold > 0 ? $m->quantity / $m->threshold : 0)
            ->values();
    }

    /**
     * Materials with zero quantity on hand.
     */
    private function allOutOfStockMaterials()
    {
        return $this->allMaterialsWithStatus()
            ->where('stock_status', 'out_of_stock')
            ->sortBy('name')
            ->values();
    }

    /**
     * Group an already-fetched materials collection by the department that
     * currently holds each item, so a report can show what each department
     * actually has on hand instead of just an aggregate count.
     */
    private function groupMaterialsByDepartment($materials)
    {
        return $materials
            ->groupBy(fn ($m) => $m->department->department_name ?? 'Unassigned')
            ->sortKeys();
    }

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

        $classifications = ProcurementClassification::where('is_active', true)
            ->orderBy('main_category')
            ->orderBy('sub_category_c')
            ->get();

        return view('supervisor.materials.index', compact(
            'materials',
            'departments',
            'categories',
            'categorySummary',
            'classifications',
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

        $classifications = ProcurementClassification::where('is_active', true)
            ->orderBy('main_category')
            ->orderBy('sub_category_c')
            ->get();

        return view(
            'supervisor.materials.create',
            compact(
                'categories',
                'units',
                'departments',
                'classifications'
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
            'classification_id' => 'nullable|exists:procurement_classifications,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('materials', 'public')
            : null;

        $material = Material::create([
            'name' => $request->name,
            'image' => $imagePath,
            'department_id' => $request->department_id,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'quantity' => $request->quantity,
            'classification_id' => $request->classification_id,
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

        $classifications = ProcurementClassification::where('is_active', true)
            ->orderBy('main_category')
            ->orderBy('sub_category_c')
            ->get();

        return view('supervisor.materials.edit', compact(
            'material',
            'categories',
            'classifications',
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
            'classification_id' => 'nullable|exists:procurement_classifications,id',

            'threshold' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        $material = Material::findOrFail($id);

        $imagePath = $material->image;

        if ($request->hasFile('image')) {

            if ($material->image) {
                Storage::disk('public')->delete($material->image);
            }

            $imagePath = $request->file('image')->store('materials', 'public');

        } elseif ($request->boolean('remove_image') && $material->image) {

            Storage::disk('public')->delete($material->image);

            $imagePath = null;
        }

        $material->update([
            'name' => $request->name,
            'image' => $imagePath,
            'department_id' => $request->department_id,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'classification_id' => $request->classification_id,

            'threshold' => $request->threshold,
        ]);

        return redirect()
            ->route('materials.index')
            ->with('success', 'Material updated successfully!');
    }

    // 📷 Quick image upload — for a material with no photo yet, straight
    // from the inventory list, without going through the full edit form.
    public function quickUpdateImage(Request $request, $id)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $material = Material::findOrFail($id);

        if ($material->image) {
            Storage::disk('public')->delete($material->image);
        }

        $path = $validated['image']->store('materials', 'public');

        $material->update(['image' => $path]);

        return response()->json([
            'image_url' => $material->fresh()->image_url,
        ]);
    }

    // 🏢 Bulk Assign Department
    public function bulkAssignDepartment(Request $request)
    {
        $validated = $request->validate([
            'material_ids' => 'required|array|min:1',
            'material_ids.*' => 'exists:materials,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        $count = Material::whereIn('id', $validated['material_ids'])
            ->update(['department_id' => $validated['department_id']]);

        $department = Department::find($validated['department_id']);

        return back()->with(
            'success',
            "{$count} material(s) reassigned to {$department->department_name}."
        );
    }

    // ➕ Quick-create a Material from inside the PPMP item modal
    public function quickStoreForProcurement(Request $request, ProcurementPlan $plan)
    {
        $user = Auth::user();

        if (! $user->hasPermission('view-ppmp') && $plan->department_id !== $user->personnel?->department_id) {

            abort(403);

        }

        $validated = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'classification_id' => 'required|exists:procurement_classifications,id',
        ]);

        $material = Material::create([
            'name' => $validated['name'],
            'department_id' => $plan->department_id,
            'category_id' => $validated['category_id'],
            'unit_id' => $validated['unit_id'],
            'classification_id' => $validated['classification_id'],
            'quantity' => 0,
            'threshold' => 1,
            'created_by' => $user->id,
        ]);

        InventoryMovement::create([
            'material_id' => $material->id,
            'movement_type' => 'initial_stock',
            'quantity' => 0,
            'previous_stock' => 0,
            'new_stock' => 0,
            'remarks' => 'Created via PPMP item entry',
            'performed_by' => $user->id,
        ]);

        MaterialLog::create([
            'material_id' => $material->id,
            'user_id' => $user->id,
            'action' => 'stock_in',
            'quantity' => 0,
            'remarks' => 'Created via PPMP item entry',
        ]);

        $material->load(['category', 'unit', 'classification']);

        return response()->json([
            'id' => $material->id,
            'name' => $material->name,
            'category' => optional($material->category)->name,
            'unit' => optional($material->unit)->name,
            'classification_code' => optional($material->classification)->code,
        ]);
    }

    public function bulkAssignClassification(Request $request)
    {
        $validated = $request->validate([
            'material_ids' => 'required|array|min:1',
            'material_ids.*' => 'exists:materials,id',
            'classification_id' => 'required|exists:procurement_classifications,id',
        ]);

        $count = Material::whereIn('id', $validated['material_ids'])
            ->update(['classification_id' => $validated['classification_id']]);

        return back()->with(
            'success',
            "{$count} material(s) classified."
        );
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

        // Personnel Material Requests
        $requestDistributions = MaterialRequestItem::with([
                'request.user',
                'request.department'
            ])
            ->where('material_id', $material->id)
            ->latest()
            ->get();

        // Walk-In Issues
        $walkinDistributions = WalkinRequestItem::with([
                'request.personnel',
                'request.department'
            ])
            ->where('material_id', $material->id)
            ->latest()
            ->get();

        // Normalize Personnel Requests
        $requestDistributions = $requestDistributions->map(function ($item) {

            return (object)[
                'source'      => 'Personnel Request',
                'reference'   => $item->request->request_number,
                'recipient'   => optional($item->request->user)->name ?? 'N/A',
                'department'  => optional($item->request->department)->department_name ?? 'N/A',
                'quantity'    => $item->quantity,
                'status'      => ucfirst($item->request->status),
                'date'        => $item->created_at,
            ];

        });

        // Normalize Walk-In Issues
        $walkinDistributions = $walkinDistributions->map(function ($item) {

            $request = $item->request;

            return (object)[
                'source'      => 'Walk-In Issue',
                'reference'   => $request->reference_no,

                'recipient'   => optional($request->personnel)->fullname
                                    ?? $request->requestor_name
                                    ?? 'N/A',

                'department'  => optional($request->department)->department_name
                                    ?? 'N/A',

                'quantity'    => $item->quantity,

                'status'      => ucfirst($request->status),

                'date'        => $request->created_at,
            ];

        });

        $distributions = $requestDistributions
        ->concat($walkinDistributions)
        ->sortByDesc('date')
        ->values();

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

        $allMaterials = $this->allMaterialsWithStatus();
        $statusCounts = $allMaterials->countBy('stock_status');

        $outOfStock = $statusCounts->get('out_of_stock', 0);
        $criticalStock = $statusCounts->get('critical', 0);
        $lowStock = $statusCounts->get('low', 0);
        $availableMaterials = $statusCounts->get('available', 0);

            $healthyMaterials = $availableMaterials;

            $inventoryHealth = $totalMaterials > 0
                ? round(($healthyMaterials / $totalMaterials) * 100)
                : 0;

            if ($inventoryHealth >= 90) {

                $healthStatus = 'Excellent';

                $statusColor = 'green';

                $recommendation =
                    'Inventory levels are excellent. Continue routine monitoring and maintain current replenishment practices.';

            } elseif ($inventoryHealth >= 75) {

                $healthStatus = 'Good';

                $statusColor = 'blue';

                $recommendation =
                    'Inventory is stable. Continue monitoring critical materials and schedule regular replenishment.';

            } elseif ($inventoryHealth >= 50) {

                $healthStatus = 'Fair';

                $statusColor = 'yellow';

                $recommendation =
                    'Inventory requires attention. Procurement should prioritize critical materials to prevent shortages.';

            } else {

                $healthStatus = 'Poor';

                $statusColor = 'red';

                $recommendation =
                    'Inventory condition is poor. Immediate procurement is recommended to prevent operational disruption.';

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

        $criticalMaterials = $allMaterials
            ->where('stock_status', 'critical')
            ->sortBy('quantity')
            ->values();

        $lowStockMaterials = $allMaterials
            ->where('stock_status', 'low')
            ->sortBy('quantity')
            ->values();

        $outOfStockMaterials = $allMaterials
            ->where('stock_status', 'out_of_stock')
            ->sortBy('name')
            ->values();

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

        $materialsByDepartment = $this->groupMaterialsByDepartment($allMaterials);

        return view(
            'supervisor.reports.inventory_summary',
        compact(
            'totalMaterials',
            'availableMaterials',
            'inventoryHealth',
            'healthStatus',
            'statusColor',
            'recommendation',
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
            'allMaterials',
            'materialsByDepartment',
        )
        );
    }

    public function inventorySummaryPrint()
    {
        $totalMaterials = Material::count();

        $allMaterials = $this->allMaterialsWithStatus();
        $statusCounts = $allMaterials->countBy('stock_status');

        $outOfStock = $statusCounts->get('out_of_stock', 0);
        $criticalMaterials = $allMaterials->where('stock_status', 'critical')->sortBy('quantity')->values();
        $criticalCount = $criticalMaterials->count();
        $lowStock = $statusCounts->get('low', 0);
        $availableMaterials = $statusCounts->get('available', 0);

        $health = $totalMaterials > 0
            ? round(($availableMaterials / $totalMaterials) * 100)
            : 0;

        $materialsByDepartment = $this->groupMaterialsByDepartment($allMaterials);

        return view(
            'supervisor.reports.inventory_summary_print',
            compact(
                'totalMaterials',
                'availableMaterials',
                'criticalMaterials',
                'criticalCount',
                'lowStock',
                'outOfStock',
                'health',
                'allMaterials',
                'materialsByDepartment'
            )
        );
    }

    public function executiveSummary()
    {
        $totalMaterials = Material::count();

        [
            'outOfStock' => $outOfStock,
            'criticalStock' => $criticalStock,
            'lowStock' => $lowStock,
            'availableMaterials' => $availableMaterials,
        ] = $this->inventoryStockBuckets();

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

            $statusColor = 'green';

            $recommendation =
                'Inventory levels are excellent. Continue routine monitoring and maintain current replenishment practices.';

        } elseif ($inventoryHealth >= 75) {

            $healthStatus = 'Good';

            $statusColor = 'blue';

            $recommendation =
                'Inventory is stable. Continue monitoring critical materials and schedule regular replenishment.';

        } elseif ($inventoryHealth >= 50) {

            $healthStatus = 'Fair';

            $statusColor = 'yellow';

            $recommendation =
                'Inventory requires attention. Procurement should prioritize critical materials to prevent shortages.';

        } else {

            $healthStatus = 'Poor';

            $statusColor = 'red';

            $recommendation =
                'Inventory condition is poor. Immediate procurement is recommended to prevent operational disruption.';
        }

        // =========================================
        // Inventory Distribution Percentages
        // =========================================

        $availablePercent = $totalMaterials > 0
            ? round(($availableMaterials / $totalMaterials) * 100)
            : 0;

        $criticalPercent = $totalMaterials > 0
            ? round(($criticalStock / $totalMaterials) * 100)
            : 0;

        $lowPercent = $totalMaterials > 0
            ? round(($lowStock / $totalMaterials) * 100)
            : 0;

        $outPercent = $totalMaterials > 0
            ? round(($outOfStock / $totalMaterials) * 100)
            : 0;

        $expiringPercent = $totalMaterials > 0
            ? round(($expiringSoon / $totalMaterials) * 100)
            : 0;

        // =========================================
        // Department Impact Analysis
        // =========================================

         $departmentImpact = Material::selectRaw('
                department_id,
                COUNT(*) as total_materials,
                 SUM(CASE WHEN quantity <= threshold THEN 1 ELSE 0 END) as affected_materials
            ')
            ->with('department')
            ->groupBy('department_id')
            ->get();

        $topCritical = $this->topCriticalMaterials(10);

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
                'statusColor',
                'recommendation',
                'topCritical',
                'availablePercent',
                'criticalPercent',
                'lowPercent',
                'outPercent',
                'departmentImpact',
                'expiringPercent'

            )
        );
    }

    public function executiveSummaryPrint()
    {
        $totalMaterials = Material::count();

        [
            'outOfStock' => $outOfStock,
            'criticalStock' => $criticalStock,
            'lowStock' => $lowStock,
            'availableMaterials' => $availableMaterials,
        ] = $this->inventoryStockBuckets();

        $expiringSoon = 0;

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

        $topCritical = $this->topCriticalMaterials(10);

        // =========================================
        // Inventory Distribution Percentages
        // =========================================

        $availablePercent = $totalMaterials > 0
            ? round(($availableMaterials / $totalMaterials) * 100)
            : 0;

        $criticalPercent = $totalMaterials > 0
            ? round(($criticalStock / $totalMaterials) * 100)
            : 0;

        $lowPercent = $totalMaterials > 0
            ? round(($lowStock / $totalMaterials) * 100)
            : 0;

        $outPercent = $totalMaterials > 0
            ? round(($outOfStock / $totalMaterials) * 100)
            : 0;

        $expiringPercent = $totalMaterials > 0
            ? round(($expiringSoon / $totalMaterials) * 100)
            : 0;


        // =========================================
        // Department Impact Analysis
        // =========================================

        $departmentImpact = Material::selectRaw("
                department_id,
                COUNT(*) as total_materials,
                SUM(
                    CASE
                        WHEN quantity <= threshold THEN 1
                        ELSE 0
                    END
                ) as affected_materials
            ")
            ->with('department')
            ->groupBy('department_id')
            ->get();

        return view(
            'supervisor.reports.executive_summary_print',
            compact(
                'totalMaterials',
                'availableMaterials',
                'criticalStock',
                'lowStock',
                'outOfStock',
                'expiringSoon',
                'inventoryHealth',
                'healthStatus',
                'topCritical',

                'availablePercent',
                'criticalPercent',
                'lowPercent',
                'outPercent',
                'expiringPercent',

                'departmentImpact'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDIVIDUAL INVENTORY REPORTS
    |--------------------------------------------------------------------------
    */

    public function criticalReport()
    {
        // Critical stock materials — at or below 30% of each item's own reorder threshold
        $criticalMaterials = $this->topCriticalMaterials(null);

        // Overall inventory count
        $totalMaterials = Material::count();

        // Statistics
        $criticalCount = $criticalMaterials->count();

        $criticalPercentage = $totalMaterials > 0
            ? round(($criticalCount / $totalMaterials) * 100, 2)
            : 0;

        $departmentsAffected = $criticalMaterials
            ->pluck('department.department_name')
            ->filter()
            ->unique()
            ->count();

        return view('supervisor.reports.critical_stock', [

            'criticalMaterials'   => $criticalMaterials,

            'criticalCount'       => $criticalCount,

            'criticalPercentage'  => $criticalPercentage,

            'departmentsAffected' => $departmentsAffected,

            'generatedDate'       => now(),

            'generatedBy'         => auth()->user(),

        ]);
    }

    public function criticalReportPrint()
    {
        $criticalMaterials = $this->topCriticalMaterials(null);

        $totalMaterials = Material::count();

        $criticalCount = $criticalMaterials->count();

        $criticalPercentage = $totalMaterials > 0
            ? round(($criticalCount / $totalMaterials) * 100, 2)
            : 0;

        $departmentsAffected = $criticalMaterials
            ->pluck('department.department_name')
            ->filter()
            ->unique()
            ->count();

        return view(
            'supervisor.reports.critical_stock_print',
            compact(
                'criticalMaterials',
                'criticalCount',
                'criticalPercentage',
                'departmentsAffected'
            )
        );
    }

    public function lowStockReport()
    {
        $lowStockMaterials = $this->allLowStockMaterials();

        $totalMaterials = Material::count();
        $lowStockCount = $lowStockMaterials->count();

        $lowStockPercentage = $totalMaterials > 0
            ? round(($lowStockCount / $totalMaterials) * 100, 2)
            : 0;

        $departmentsAffected = $lowStockMaterials
            ->pluck('department.department_name')
            ->filter()
            ->unique()
            ->count();

        return view('supervisor.reports.low_stock', [

            'lowStockMaterials'   => $lowStockMaterials,

            'lowStockCount'       => $lowStockCount,

            'lowStockPercentage'  => $lowStockPercentage,

            'departmentsAffected' => $departmentsAffected,

        ]);
    }

    public function lowStockReportPrint()
    {
        $lowStockMaterials = $this->allLowStockMaterials();

        $totalMaterials = Material::count();
        $lowStockCount = $lowStockMaterials->count();

        $lowStockPercentage = $totalMaterials > 0
            ? round(($lowStockCount / $totalMaterials) * 100, 2)
            : 0;

        $departmentsAffected = $lowStockMaterials
            ->pluck('department.department_name')
            ->filter()
            ->unique()
            ->count();

        return view(
            'supervisor.reports.low_stock_print',
            compact(
                'lowStockMaterials',
                'lowStockCount',
                'lowStockPercentage',
                'departmentsAffected'
            )
        );
    }

    public function outOfStockReport()
    {
        return view(
            'supervisor.reports.out_of_stock',
            $this->outOfStockReportData()
        );
    }

    public function outOfStockReportPrint()
    {
        return view(
            'supervisor.reports.out_of_stock_print',
            $this->outOfStockReportData()
        );
    }

    private function outOfStockReportData(): array
    {
        $outOfStockMaterials = $this->allOutOfStockMaterials();

        $totalMaterials = Material::count();
        $outOfStockCount = $outOfStockMaterials->count();

        $outOfStockPercentage = $totalMaterials > 0
            ? round(($outOfStockCount / $totalMaterials) * 100, 2)
            : 0;

        $departmentsAffected = $outOfStockMaterials
            ->pluck('department.department_name')
            ->filter()
            ->unique()
            ->count();

        return compact(
            'outOfStockMaterials',
            'outOfStockCount',
            'outOfStockPercentage',
            'departmentsAffected'
        );
    }

    public function expirationReport()
    {
        return view(
            'supervisor.reports.expiration_report',
            $this->expirationReportData()
        );
    }

    public function expirationReportPrint()
    {
        return view(
            'supervisor.reports.expiration_report_print',
            $this->expirationReportData()
        );
    }

    private function expirationReportData(): array
    {
        $expiringMaterials = MaterialRestockLog::with(['material.department', 'material.category'])
            ->where('has_expiration', 1)
            ->where('quantity_remaining', '>', 0)
            ->whereDate('expiration_date', '<=', now()->addDays(30))
            ->whereDate('expiration_date', '>=', now())
            ->orderBy('expiration_date')
            ->get();

        $expiredMaterials = MaterialRestockLog::with(['material.department', 'material.category'])
            ->where('has_expiration', 1)
            ->where('quantity_remaining', '>', 0)
            ->whereDate('expiration_date', '<', now())
            ->orderBy('expiration_date')
            ->get();

        $expiringCount = $expiringMaterials->count();
        $expiredCount = $expiredMaterials->count();

        $departmentsAffected = $expiringMaterials
            ->merge($expiredMaterials)
            ->pluck('material.department.department_name')
            ->filter()
            ->unique()
            ->count();

        return compact(
            'expiringMaterials',
            'expiredMaterials',
            'expiringCount',
            'expiredCount',
            'departmentsAffected'
        );
    }

    public function departmentSummaryReport()
    {
        return view(
            'supervisor.reports.department_summary',
            $this->departmentSummaryReportData()
        );
    }

    public function departmentSummaryReportPrint()
    {
        return view(
            'supervisor.reports.department_summary_print',
            $this->departmentSummaryReportData()
        );
    }

    private function departmentSummaryReportData(): array
    {
        $allMaterials = $this->allMaterialsWithStatus();
        $materialsByDepartment = $this->groupMaterialsByDepartment($allMaterials);

        $departmentRows = $materialsByDepartment->map(function ($materials, $departmentName) {
            $statusCounts = $materials->countBy('stock_status');

            return (object) [
                'department_name'  => $departmentName,
                'total_materials'  => $materials->count(),
                'total_quantity'   => $materials->sum('quantity'),
                'critical_count'   => $statusCounts->get('critical', 0),
                'low_count'        => $statusCounts->get('low', 0),
                'out_of_stock_count' => $statusCounts->get('out_of_stock', 0),
            ];
        })->values();

        $totalMaterials = $allMaterials->count();
        $totalDepartments = $departmentRows->count();
        $totalQuantity = $allMaterials->sum('quantity');

        $departmentsWithIssues = $departmentRows
            ->filter(fn ($row) => $row->critical_count + $row->low_count + $row->out_of_stock_count > 0)
            ->count();

        return compact(
            'departmentRows',
            'totalMaterials',
            'totalDepartments',
            'totalQuantity',
            'departmentsWithIssues'
        );
    }

    public function purchaseRecommendations()
    {
        return view(
            'supervisor.reports.purchase_recommendations',
            $this->purchaseRecommendationsData()
        );
    }

    public function purchaseRecommendationsPrint()
    {
        return view(
            'supervisor.reports.purchase_recommendations_print',
            $this->purchaseRecommendationsData()
        );
    }

    /**
     * What a procurement officer needs to buy right now: low/critical/out
     * of stock materials, plus anything about to expire this month.
     */
    private function purchaseRecommendationsData(): array
    {
        $needsPurchase = $this->allMaterialsWithStatus()
            ->whereIn('stock_status', ['out_of_stock', 'critical', 'low'])
            ->sortBy(fn ($m) => $m->threshold > 0 ? $m->quantity / $m->threshold : 0)
            ->values();

        $expiringThisMonth = MaterialRestockLog::with(['material.department', 'material.category'])
            ->where('has_expiration', 1)
            ->where('quantity_remaining', '>', 0)
            ->whereMonth('expiration_date', now()->month)
            ->whereYear('expiration_date', now()->year)
            ->orderBy('expiration_date')
            ->get();

        return [
            'needsPurchase' => $needsPurchase,
            'needsPurchaseCount' => $needsPurchase->count(),
            'expiringThisMonth' => $expiringThisMonth,
            'expiringThisMonthCount' => $expiringThisMonth->count(),
            'monthLabel' => now()->format('F Y'),
        ];
    }

    public function frequentlyRequestedReport()
    {
        return view(
            'supervisor.reports.frequently_requested',
            $this->frequentlyRequestedData()
        );
    }

    public function frequentlyRequestedReportPrint()
    {
        return view(
            'supervisor.reports.frequently_requested_print',
            $this->frequentlyRequestedData()
        );
    }

    /**
     * Materials with the highest combined demand from Material Requests and
     * Walk-In Issuances — the items that matter most to keep well stocked.
     */
    private function frequentlyRequestedData(): array
    {
        $requestDemand = MaterialRequestItem::selectRaw('material_id, SUM(quantity) as qty, COUNT(DISTINCT request_id) as tx')
            ->groupBy('material_id')
            ->get()
            ->keyBy('material_id');

        $walkinDemand = WalkinRequestItem::selectRaw('material_id, SUM(quantity) as qty, COUNT(DISTINCT walkin_request_id) as tx')
            ->groupBy('material_id')
            ->get()
            ->keyBy('material_id');

        $demandMaterialIds = $requestDemand->keys()->merge($walkinDemand->keys())->unique();

        $frequentlyRequested = Material::with(['department', 'category'])
            ->whereIn('id', $demandMaterialIds)
            ->get()
            ->map(function ($material) use ($requestDemand, $walkinDemand) {

                $material->total_requested_qty =
                    ($requestDemand->get($material->id)?->qty ?? 0) +
                    ($walkinDemand->get($material->id)?->qty ?? 0);

                $material->total_transactions =
                    ($requestDemand->get($material->id)?->tx ?? 0) +
                    ($walkinDemand->get($material->id)?->tx ?? 0);

                return $material;
            })
            ->sortByDesc('total_transactions')
            ->take(20)
            ->values();

        $totalMaterials = Material::count();

        return [
            'frequentlyRequested' => $frequentlyRequested,
            'frequentlyRequestedCount' => $frequentlyRequested->count(),
            'totalTransactions' => $frequentlyRequested->sum('total_transactions'),
            'totalQtyRequested' => $frequentlyRequested->sum('total_requested_qty'),
            'topItem' => $frequentlyRequested->first(),
            'totalMaterials' => $totalMaterials,
        ];
    }

    public function nonMovableReport()
    {
        return view(
            'supervisor.reports.non_movable',
            $this->nonMovableData()
        );
    }

    public function nonMovableReportPrint()
    {
        return view(
            'supervisor.reports.non_movable_print',
            $this->nonMovableData()
        );
    }

    /**
     * Materials with zero Material Request or Walk-In Issuance activity —
     * candidates for reassignment rather than repurchase.
     */
    private function nonMovableData(): array
    {
        $nonMovable = Material::with(['department', 'category'])
            ->whereDoesntHave('requestItems')
            ->whereDoesntHave('walkinItems')
            ->orderBy('name')
            ->get();

        $totalMaterials = Material::count();

        $nonMovableCount = $nonMovable->count();

        $nonMovablePercentage = $totalMaterials > 0
            ? round(($nonMovableCount / $totalMaterials) * 100, 2)
            : 0;

        $departmentsAffected = $nonMovable
            ->pluck('department.department_name')
            ->filter()
            ->unique()
            ->count();

        return [
            'nonMovable' => $nonMovable,
            'nonMovableCount' => $nonMovableCount,
            'nonMovablePercentage' => $nonMovablePercentage,
            'departmentsAffected' => $departmentsAffected,
        ];
    }

    public function details(Material $material)
    {
        $material->load([
            'unit',
            'category'
        ]);

        return response()->json([

            'id' => $material->id,

            'name' => $material->name,

            'unit' => optional($material->unit)->name,

            'category' => optional($material->category)->name,

            // Current default cost used by PPMP
            'estimated_unit_cost' => $material->cost,

            // Future features
            'quantity' => $material->quantity,

            'threshold' => $material->threshold,

            'classification_id' => $material->classification_id,

        ]);
    }

}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\Personnel;
use App\Models\User;
use App\Models\Notification;
use App\Events\NewNotificationEvent;
use App\Models\MaterialLog;
use App\Models\MaterialRestockLog;
use App\Models\Department;
use App\Models\InventoryMovement;
use App\Models\DepartmentMaterial;


class MaterialRequestController extends Controller
{
    // 📄 Show form
    public function create()
    {
        $materials = Material::all();

        $departments = Department::all();

        return view(
            'material_request.form',
            compact(
                'materials',
                'departments'
            )
        );
    }

    // 📜 Personnel Request History
    public function history()
    {
        $requests = MaterialRequest::with([
            'items.material',
            'department'
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

        $pendingCount = $requests
            ->where('status', 'pending')
            ->count();

        $approvedCount = $requests
            ->where('status', 'approved')
            ->count();

        $releasedCount = $requests
            ->where('status', 'released')
            ->count();

        $rejectedCount = $requests
            ->where('status', 'rejected')
            ->count();

        return view(
            'material_request.history',
            compact(
                'requests',
                'pendingCount',
                'approvedCount',
                'releasedCount',
                'rejectedCount'
            )
        );
    }

    // 🖨 Request Slip
    public function slip($id)
    {
        $request = MaterialRequest::with([
            'user',
            'items.material'
        ])->findOrFail($id);

        return view(
            'material_request.slip',
            compact('request')
        );
    }

    // 💾 Store request
    
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',

            'material_id' => 'required|array',

            'material_id.*' => 'required|exists:materials,id',

            'quantity' => 'required|array',

            'quantity.*' => 'required|integer|min:1',

            'purpose' => 'required|string|max:500',
        ]);

        $personnel = Personnel::where('user_id', Auth::id())->first();

        if (!$personnel) {
            return back()->with('error', 'Personnel not found');
        }

        // ✅ Validate stock first
        foreach ($request->material_id as $index => $materialId) {

            $material = Material::find($materialId);

            $qty = $request->quantity[$index];

            if (!$material) {
                return back()->with('error', 'Invalid material selected.');
            }

            // ❌ Prevent exceed stock
            if ($qty > $material->quantity) {

                return back()->with(
                    'error',
                    'Requested quantity exceeds available stock for '
                    . $material->name
                );
            }
        }

        // ✅ Create request header
        $latestId = MaterialRequest::max('id') + 1;

        $requestNumber =
            'MR-'
            . date('Y')
            . '-'
            . str_pad($latestId, 4, '0', STR_PAD_LEFT);

        $materialRequest = MaterialRequest::create([

            'user_id' => Auth::id(),

            'department_id' => $request->department_id,

            'request_number' => $requestNumber,

            'status' => 'pending',

            'purpose' => $request->purpose,

        ]);

        // ✅ Save multiple items
        foreach ($request->material_id as $index => $materialId) {

            MaterialRequestItem::create([
                'request_id' => $materialRequest->id,
                'material_id' => $materialId,
                'quantity' => $request->quantity[$index],
            ]);
        }

        // 🔔 Notify supervisors
        $supervisors = User::where('role', 'supervisor')->get();

        foreach ($supervisors as $admin) {

            Notification::create([
                'user_id' => $admin->id,
                'type' => 'material',
                'title' => 'New Material Request',
                'url' => '/supervisor/material-requests',
                'message' =>
                    (Auth::user()->fullname ?? Auth::user()->username)
                    . ' submitted a material request.',
                'is_read' => 0
            ]);
        }

        return back()->with(
            'success',
            'Material request submitted successfully!'
        );
    }

    // 📊 Supervisor view (NEW FUNCTION)
        public function index()
        {
            if (!auth()->check() || auth()->user()->role !== 'supervisor') {
                abort(403);
            }

            $requests = MaterialRequest::with([
                'user',
                'department',
                'items.material'
            ])->latest()->get();

            $pendingCount = $requests
                ->where('status', 'pending')
                ->count();

            $approvedCount = $requests
                ->where('status', 'approved')
                ->count();

            $releasedCount = $requests
                ->where('status', 'released')
                ->count();

            $rejectedCount = $requests
                ->where('status', 'rejected')
                ->count();

            return view(
                'material_request.admin',
                compact(
                    'requests',
                    'pendingCount',
                    'approvedCount',
                    'releasedCount',
                    'rejectedCount'
                )
            );
        }

        

    // ✅ Approve request
    public function approve($id)
    {
        
        $materialRequest = MaterialRequest::with([
            'items'
        ])->findOrFail($id);

        // ❌ Prevent double approval
        if ($materialRequest->status === 'approved') {

            return back()->with(
                'error',
                'Request already approved.'
            );
        }
        


        

        foreach ($materialRequest->items as $item) {
            $material = $item->material;

            // ❗ Prevent negative stock
            if ($material->quantity < $item->quantity) {
                return back()->with('error', 'Not enough stock for ' . $material->name);
            }

            // 🔻 SAVE PREVIOUS STOCK
            $previousQuantity = $material->quantity;

            /*
            |--------------------------------------------------------------------------
            | FIFO BATCH DEDUCTION
            |--------------------------------------------------------------------------
            */

            $remainingRequestQty = $item->quantity;

            $batches = MaterialRestockLog::where(
                    'material_id',
                    $material->id
                )
                ->where('quantity_remaining', '>', 0)
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($batches as $batch) {

                if ($remainingRequestQty <= 0) {
                    break;
                }

                if ($batch->quantity_remaining >= $remainingRequestQty) {

                    $batch->quantity_remaining -= $remainingRequestQty;

                    $batch->save();

                    $remainingRequestQty = 0;

                } else {

                    $remainingRequestQty -= $batch->quantity_remaining;

                    $batch->quantity_remaining = 0;

                    $batch->save();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE MATERIAL STOCK
            |--------------------------------------------------------------------------
            */

            $material->quantity -= $item->quantity;

            $material->save();

            // ✅ SAVE INVENTORY MOVEMENT
            
            InventoryMovement::create([

                'material_id' => $material->id,

                'performed_by' => auth()->id(),

                'movement_type' => 'request',

                'quantity' => $item->quantity,

                'previous_stock' => $previousQuantity,

                'new_stock' => $material->quantity,

                'remarks' =>
                    'Request #: '
                    . ($materialRequest->request_number ?? 'N/A')

                    . ' | Requested by: '
                    . ($materialRequest->user->fullname
                        ?? $materialRequest->user->username)

            ]);


            // 📝 AUTO MATERIAL LOG
            MaterialLog::create([

                'material_id' => $material->id,

                'user_id' => auth()->id(),

                'action' => 'deducted',

                'quantity' => $item->quantity,

                'remarks' =>
                    'Request #: '
                    . ($materialRequest->request_number ?? 'N/A')

                    . ' | Requested by: '
                    . ($materialRequest->user->fullname
                        ?? $materialRequest->user->username)

                    . ' | Approved by: '
                    . (auth()->user()->fullname
                        ?? auth()->user()->username)

            ]);

            
            // ❌ OUT OF STOCK ALERT
            if ($material->quantity <= 0) {

                $notif = Notification::create([

                    'user_id' => auth()->id(),

                    'type' => 'inventory',

                    'title' => 'Out of Stock Alert',
                    
                    'url' => route('materials.index'),

                    'message' =>
                        $material->name .
                        ' is now OUT OF STOCK.',

                    'is_read' => 0
                ]);

                event(new NewNotificationEvent($notif));
            }

            // 🚨 CRITICAL STOCK ALERT
            elseif ($material->quantity <= 5) {

                $notif = Notification::create([

                    'user_id' => auth()->id(),

                    'type' => 'inventory',

                    'title' => 'Critical Stock Alert',

                    'url' => route('materials.index'),

                    'message' =>
                        $material->name .
                        ' stock is critically low (' .
                        $material->quantity .
                        ' remaining).',

                    'is_read' => 0
                ]);

                event(new NewNotificationEvent($notif));
            }

            // ⚠ LOW STOCK ALERT
            elseif ($material->quantity <= $material->threshold) {

                $notif = Notification::create([

                    'user_id' => auth()->id(),

                    'type' => 'inventory',

                    'title' => 'Low Stock Alert',

                    'url' => route('materials.index'),

                    'message' =>
                        $material->name .
                        ' inventory is getting low.',

                    'is_read' => 0
                ]);

                event(new NewNotificationEvent($notif));
            }

        }

        $materialRequest->update([
            'status' => 'approved'
        ]);

        // 🔔 Notify requester
        
        $notif = Notification::create([
            'user_id' => $materialRequest->user_id,
            'type' => 'material',
            'title' => 'Request Approved',
            'url' => '/material-request/history',
            'message' => 'Your material request has been approved.',
            'is_read' => 0
        ]);

    
    
        event(new NewNotificationEvent($notif));

        return back()->with('success', 'Request approved and stock updated!');

    }

    // ❌ Reject request
  
    public function reject($id)
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        // ❌ Prevent rejecting approved requests
        if ($materialRequest->status === 'approved') {

            return back()->with(
                'error',
                'Approved requests cannot be rejected.'
            );
        }

        $materialRequest->update([
            'status' => 'rejected'
        ]);

        
        $notif = Notification::create([
            'user_id' => $materialRequest->user_id,
            'type' => 'material',
            'title' => 'Request Rejected',
            'url' => '/material-request/history',
            'message' => 'Your material request has been rejected.',
            'is_read' => 0
        ]);

        event(new NewNotificationEvent($notif));
    
        return back()->with('success', 'Request rejected successfully.');
}

/*
|--------------------------------------------------------------------------
| 📦 Release Request
|--------------------------------------------------------------------------
*/
public function release($id)
{
    $materialRequest = MaterialRequest::findOrFail($id);

    if ($materialRequest->status !== 'approved') {

        return back()->with(
            'error',
            'Only approved requests can be released.'
        );
    }

    $materialRequest->update([

        'status' => 'released',

        'released_by' => auth()->id(),

        'released_at' => now()

    ]);

    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT MATERIAL TRACKING
    |--------------------------------------------------------------------------
    */

    foreach ($materialRequest->items as $item) {

        DepartmentMaterial::create([

            'department_id' => $materialRequest->department_id,

            'material_id' => $item->material_id,

            'quantity' => $item->quantity,

            'request_id' => $materialRequest->id,

            'released_by' => auth()->id(),

            'released_at' => now()

        ]);
    }

    $notif = Notification::create([

        'user_id' => $materialRequest->user_id,

        'type' => 'material',

        'title' => 'Materials Released',

        'url' => '/material-request/history',

        'message' =>
            'Your requested materials are ready for pickup.',

        'is_read' => 0

    ]);

    event(new NewNotificationEvent($notif));

    return back()->with(
        'success',
        'Materials released successfully.'
    );
}

}


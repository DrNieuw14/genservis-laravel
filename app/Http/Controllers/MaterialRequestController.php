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

class MaterialRequestController extends Controller
{
    // 📄 Show form
    public function create()
    {
        $materials = Material::all();

        return view('material_request.form', compact('materials'));
    }

    // 📜 Personnel Request History
    public function history()
    {
        $requests = MaterialRequest::with([
            'items.material'
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

        return view('material_request.history', compact('requests'));
    }

    // 💾 Store request
    
    public function store(Request $request)
    {
        $request->validate([
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
        $materialRequest = MaterialRequest::create([
            'user_id' => Auth::id(),
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
        // ❌ block non-supervisor
        if (auth()->user()->role !== 'supervisor') {
            abort(403);
        }

        // 📦 get all requests with materials + user
        $requests = MaterialRequest::with([
            'user',
            'items.material'
        ])->latest()->get();

        // 📄 send to view
        return view('material_request.admin', compact('requests'));
    }

    // ✅ Approve request
    public function approve($id)
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        foreach ($materialRequest->items as $item) {
            $material = $item->material;

            // ❗ Prevent negative stock
            if ($material->quantity < $item->quantity) {
                return back()->with('error', 'Not enough stock for ' . $material->name);
            }

            // 🔻 Deduct stock
            $material->quantity -= $item->quantity;
            $material->save();

            // 🔔 LOW STOCK ALERT (THIS IS YOUR NEW FEATURE)
            if ($material->quantity <= $material->threshold) {

                $notif = Notification::create([
                    'user_id' => auth()->id(), // supervisor
                    'type' => 'material',
                    'title' => 'Low Stock Alert',
                    'message' => $material->name . ' is running low on stock!',
                    'is_read' => 0
                ]);

                event(new NewNotificationEvent($notif));
            }
        }

        $materialRequest->update([
            'status' => 'approved'
        ]);

        // 🔔 Notify requester
        Notification::create([
            'user_id' => $materialRequest->user_id,
            'type' => 'material',
            'title' => 'Request Approved',
            'message' => 'Your material request has been approved.',
            'is_read' => 0
        ]);

        return back()->with('success', 'Request approved!');
    }

    // ❌ Reject request
    public function reject($id)
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        $materialRequest->update([
        'status' => 'rejected'
    ]);

    Notification::create([
        'user_id' => $materialRequest->user_id,
            'type' => 'material',
            'title' => 'Request Rejected',
            'message' => 'Your material request has been rejected.',
            'is_read' => 0
        ]);

        return back()->with('success', 'Request rejected!');
    }

}
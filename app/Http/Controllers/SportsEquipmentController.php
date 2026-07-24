<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\Department;
use App\Models\Notification;
use App\Models\SportsEquipment;
use App\Models\SportsEquipmentBorrow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SportsEquipmentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Equipment Catalog (Inventory Custodian — manage-sports-equipment-inventory)
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        abort_unless(Auth::user()->hasPermission('manage-sports-equipment-inventory'), 403);

        $equipments = SportsEquipment::orderBy('name')->get();

        return view('sports_equipment.index', compact('equipments'));
    }

    public function create()
    {
        abort_unless(Auth::user()->hasPermission('manage-sports-equipment-inventory'), 403);

        return view('sports_equipment.create');
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->hasPermission('manage-sports-equipment-inventory'), 403);

        $validated = $this->validateEquipment($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sports_equipment', 'public');
        }

        $validated['created_by'] = Auth::id();

        SportsEquipment::create($validated);

        return redirect()
            ->route('sports-equipment.index')
            ->with('success', 'Sports equipment added.');
    }

    public function edit(SportsEquipment $sportsEquipment)
    {
        abort_unless(Auth::user()->hasPermission('manage-sports-equipment-inventory'), 403);

        return view('sports_equipment.edit', ['equipment' => $sportsEquipment]);
    }

    public function update(Request $request, SportsEquipment $sportsEquipment)
    {
        abort_unless(Auth::user()->hasPermission('manage-sports-equipment-inventory'), 403);

        $validated = $this->validateEquipment($request);

        // Can't shrink total below what's currently out on loan — that
        // would silently make availableQuantity() go negative.
        if ($validated['total_quantity'] < $sportsEquipment->borrowedQuantity()) {
            return back()->with(
                'error',
                'Total quantity cannot be less than the ' . $sportsEquipment->borrowedQuantity() . ' unit(s) currently borrowed.'
            );
        }

        if ($request->hasFile('image')) {

            if ($sportsEquipment->image) {
                Storage::disk('public')->delete($sportsEquipment->image);
            }

            $validated['image'] = $request->file('image')->store('sports_equipment', 'public');
        }

        $sportsEquipment->update($validated);

        return redirect()
            ->route('sports-equipment.index')
            ->with('success', 'Sports equipment updated.');
    }

    public function destroy(SportsEquipment $sportsEquipment)
    {
        abort_unless(Auth::user()->hasPermission('manage-sports-equipment-inventory'), 403);

        if ($sportsEquipment->borrows()->whereIn('status', ['pending', 'approved'])->exists()) {
            return back()->with('error', 'Cannot delete equipment with pending or active borrow requests.');
        }

        if ($sportsEquipment->image) {
            Storage::disk('public')->delete($sportsEquipment->image);
        }

        $sportsEquipment->delete();

        return redirect()
            ->route('sports-equipment.index')
            ->with('success', 'Sports equipment removed.');
    }

    private function validateEquipment(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'total_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Borrow Requests (submitted by any user, from the Material Request page)
    |--------------------------------------------------------------------------
    */

    // Mirrors Material Request's multi-item cart: one submission can borrow
    // several different equipment types at once, but each becomes its own
    // SportsEquipmentBorrow row — a basketball and a volleyball net don't
    // necessarily come back on the same day, so they need independent
    // approve/reject/return tracking rather than one shared status.
    public function storeBorrow(Request $request)
    {
        $equipmentIds = $request->input('sports_equipment_id', []);
        $quantities = $request->input('quantity', []);

        $cleanEquipmentIds = [];
        $cleanQuantities = [];

        foreach ($equipmentIds as $index => $equipmentId) {

            $qty = $quantities[$index] ?? null;

            if (!empty($equipmentId) && !empty($qty)) {
                $cleanEquipmentIds[] = $equipmentId;
                $cleanQuantities[] = $qty;
            }
        }

        $request->merge([
            'sports_equipment_id' => $cleanEquipmentIds,
            'quantity' => $cleanQuantities,
        ]);

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',

            'sports_equipment_id' => 'required|array|min:1',
            'sports_equipment_id.*' => 'required|exists:sports_equipments,id',

            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',

            'room' => 'nullable|string|max:100',
            'purpose' => 'required|string|max:500',
            'expected_return_date' => 'required|date|after_or_equal:today',
        ], [
            'sports_equipment_id.required' => 'Please add at least one equipment item to borrow.',
            'sports_equipment_id.min' => 'Please add at least one equipment item to borrow.',
            'expected_return_date.after_or_equal' => 'Expected return date cannot be in the past.',
        ]);

        // Validate stock first, before creating anything
        foreach ($validated['sports_equipment_id'] as $index => $equipmentId) {

            $equipment = SportsEquipment::find($equipmentId);
            $qty = $validated['quantity'][$index];

            if ($qty > $equipment->availableQuantity()) {
                return back()->with(
                    'error',
                    'Only ' . $equipment->availableQuantity() . ' ' . $equipment->unit . ' of ' . $equipment->name . ' available to borrow.'
                );
            }
        }

        $createdBorrows = [];

        foreach ($validated['sports_equipment_id'] as $index => $equipmentId) {

            $equipment = SportsEquipment::find($equipmentId);

            $createdBorrows[] = SportsEquipmentBorrow::create([
                'borrow_number' => SportsEquipmentBorrow::generateBorrowNumber(),
                'sports_equipment_id' => $equipment->id,
                'user_id' => Auth::id(),
                'department_id' => $validated['department_id'],
                'quantity' => $validated['quantity'][$index],
                'room' => $validated['room'] ?? null,
                'purpose' => $validated['purpose'],
                'expected_return_date' => $validated['expected_return_date'],
                'status' => 'pending',
            ]);
        }

        $approvers = User::withPermission('approve-sports-equipment-borrows')->get();

        $itemSummary = collect($createdBorrows)
            ->map(fn ($b) => $b->equipment->name)
            ->implode(', ');

        foreach ($approvers as $approver) {

            $notif = Notification::create([
                'user_id' => $approver->id,
                'type' => 'sports_equipment',
                'title' => 'New Sports Equipment Borrow Request',
                'url' => route('sports-equipment.borrows.index'),
                'message' =>
                    (Auth::user()->fullname ?? Auth::user()->username)
                    . ' requested to borrow: ' . $itemSummary . '.',
                'is_read' => 0,
            ]);

            event(new NewNotificationEvent($notif));
        }

        return back()->with(
            'success',
            'Borrow request submitted! Reference #: ' . collect($createdBorrows)->pluck('borrow_number')->implode(', ')
        );
    }

    public function borrowsIndex()
    {
        $user = Auth::user();

        abort_unless(
            $user->hasPermission('manage-sports-equipment-inventory')
                || $user->hasPermission('approve-sports-equipment-borrows'),
            403
        );

        $borrows = SportsEquipmentBorrow::with(['equipment', 'user', 'department'])
            ->latest()
            ->get();

        $canAct = $user->hasPermission('approve-sports-equipment-borrows');

        return view('sports_equipment.borrows', compact('borrows', 'canAct'));
    }

    public function approveBorrow(SportsEquipmentBorrow $borrow)
    {
        abort_unless(Auth::user()->hasPermission('approve-sports-equipment-borrows'), 403);

        if ($borrow->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        if ($borrow->quantity > $borrow->equipment->availableQuantity()) {
            return back()->with('error', 'Not enough ' . $borrow->equipment->name . ' available to approve this request.');
        }

        $borrow->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $notif = Notification::create([
            'user_id' => $borrow->user_id,
            'type' => 'sports_equipment',
            'title' => 'Borrow Request Approved',
            'url' => route('sports-equipment.my-borrows'),
            'message' => 'Your request to borrow ' . $borrow->equipment->name . ' has been approved.',
            'is_read' => 0,
        ]);

        event(new NewNotificationEvent($notif));

        return back()->with('success', 'Borrow request approved.');
    }

    public function rejectBorrow(Request $request, SportsEquipmentBorrow $borrow)
    {
        abort_unless(Auth::user()->hasPermission('approve-sports-equipment-borrows'), 403);

        if ($borrow->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $borrow->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        $notif = Notification::create([
            'user_id' => $borrow->user_id,
            'type' => 'sports_equipment',
            'title' => 'Borrow Request Rejected',
            'url' => route('sports-equipment.my-borrows'),
            'message' => 'Your request to borrow ' . $borrow->equipment->name . ' has been rejected.',
            'is_read' => 0,
        ]);

        event(new NewNotificationEvent($notif));

        return back()->with('success', 'Borrow request rejected.');
    }

    public function markReturned(Request $request, SportsEquipmentBorrow $borrow)
    {
        abort_unless(Auth::user()->hasPermission('approve-sports-equipment-borrows'), 403);

        if ($borrow->status !== 'approved') {
            return back()->with('error', 'Only borrowed (approved) equipment can be marked as returned.');
        }

        $validated = $request->validate([
            'condition_on_return' => 'required|in:' . implode(',', SportsEquipmentBorrow::CONDITIONS),
            'remarks' => 'nullable|string|max:500',
        ]);

        $borrow->update([
            'status' => 'returned',
            'actual_return_date' => now(),
            'condition_on_return' => $validated['condition_on_return'],
            'returned_confirmed_by' => Auth::id(),
            'remarks' => $validated['remarks'] ?? null,
        ]);

        $notif = Notification::create([
            'user_id' => $borrow->user_id,
            'type' => 'sports_equipment',
            'title' => 'Equipment Return Logged',
            'url' => route('sports-equipment.my-borrows'),
            'message' => 'Your return of ' . $borrow->equipment->name . ' has been logged.',
            'is_read' => 0,
        ]);

        event(new NewNotificationEvent($notif));

        return back()->with('success', 'Return logged successfully.');
    }

    public function myBorrows()
    {
        $borrows = SportsEquipmentBorrow::with(['equipment', 'department'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('sports_equipment.my_borrows', compact('borrows'));
    }

    /*
    |--------------------------------------------------------------------------
    | Data for the Material Request page's "Borrow Sports Equipment" mode
    |--------------------------------------------------------------------------
    */

    public static function catalogForJs()
    {
        return SportsEquipment::all()->map(function ($equipment) {
            return [
                'id' => $equipment->id,
                'name' => $equipment->name,
                'unit' => $equipment->unit,
                'image_url' => $equipment->image_url,
                'available' => $equipment->availableQuantity(),
            ];
        })->values();
    }
}

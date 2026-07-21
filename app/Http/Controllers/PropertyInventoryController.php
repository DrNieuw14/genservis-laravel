<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\PropertyItem;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyInventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $rooms = Room::with(['department', 'propertyItems'])
            ->when($search, function ($query) use ($search) {
                $query->where(fn ($q) => $q
                    ->where('room_name', 'like', "%{$search}%")
                    ->orWhere('building', 'like', "%{$search}%"));
            })
            ->orderBy('room_name')
            ->paginate(10)
            ->withQueryString();

        return view('property_inventory.index', [
            'rooms' => $rooms,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('property_inventory.create', [
            'departments' => Department::orderBy('department_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            // Free text so "Other" can be any custom room type (e.g.
            // "Networking Laboratory") — the fixed list is just a UI
            // shortcut, not a hard constraint on the column.
            'room_type' => 'nullable|string|max:255',
            'room_type_other' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        $validated = $this->resolveRoomType($validated);
        $validated['created_by'] = Auth::id();

        $room = Room::create($validated);

        return redirect()
            ->route('property-inventory.show', $room->id)
            ->with('success', 'Room added successfully.');
    }

    public function show(Room $room)
    {
        $room->load(['department', 'propertyItems' => fn ($q) => $q->orderBy('property_name')]);

        return view('property_inventory.show', compact('room'));
    }

    public function edit(Room $room)
    {
        return view('property_inventory.edit', [
            'room' => $room,
            'departments' => Department::orderBy('department_name')->get(),
        ]);
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            // Free text so "Other" can be any custom room type (e.g.
            // "Networking Laboratory") — the fixed list is just a UI
            // shortcut, not a hard constraint on the column.
            'room_type' => 'nullable|string|max:255',
            'room_type_other' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated = $this->resolveRoomType($validated);
        $validated['is_active'] = $request->boolean('is_active');

        $room->update($validated);

        return redirect()
            ->route('property-inventory.show', $room->id)
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()
            ->route('property-inventory.index')
            ->with('success', 'Room and its property items removed.');
    }

    public function storeItem(Request $request, Room $room)
    {
        $validated = $this->validateItem($request);
        $validated['created_by'] = Auth::id();

        $room->propertyItems()->create($validated);

        $response = back()->with('success', 'Property item added.');

        if ($request->input('action') === 'add_another') {
            $response->with('reopen_add_item', true);
        }

        return $response;
    }

    public function updateItem(Request $request, Room $room, PropertyItem $item)
    {
        abort_if($item->room_id !== $room->id, 404);

        $item->update($this->validateItem($request));

        return back()->with('success', 'Property item updated.');
    }

    public function destroyItem(Room $room, PropertyItem $item)
    {
        abort_if($item->room_id !== $room->id, 404);

        $item->delete();

        return back()->with('success', 'Property item removed.');
    }

    public function print(Room $room)
    {
        $room->load(['department', 'propertyItems' => fn ($q) => $q->orderBy('property_name')]);

        return view('property_inventory.print', compact('room'));
    }

    // When "Other" is picked, the actual room_type stored is whatever the
    // user typed into the accompanying free-text field — room_type_other
    // is never itself a column, just a carrier for that custom value.
    private function resolveRoomType(array $validated): array
    {
        if ($validated['room_type'] === 'Other' && !empty($validated['room_type_other'])) {
            $validated['room_type'] = $validated['room_type_other'];
        }

        unset($validated['room_type_other']);

        return $validated;
    }

    private function validateItem(Request $request): array
    {
        return $request->validate([
            'property_name' => 'required|string|max:255',
            'property_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'unit_value' => 'nullable|numeric|min:0',
            'date_acquired' => 'nullable|date',
            'condition' => 'required|in:' . implode(',', PropertyItem::CONDITIONS),
            'remarks' => 'nullable|string',
        ]);
    }
}

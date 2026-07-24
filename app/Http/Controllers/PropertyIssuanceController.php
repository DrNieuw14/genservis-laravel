<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\Notification;
use App\Models\Personnel;
use App\Models\PropertyIssuance;
use App\Models\PropertyIssuancePhoto;
use App\Models\PropertyItem;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PropertyIssuanceController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $roomId = $request->query('room_id');

        $baseQuery = PropertyIssuance::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('issued_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('issued_at', '<=', $dateTo))
            ->when($roomId, fn ($q) => $q->where('room_id', $roomId));

        $totalSlips = (clone $baseQuery)->count();
        $totalIcs = (clone $baseQuery)->whereIn('form_type', ['ics_5k_below', 'ics_mid'])->count();
        $totalPar = (clone $baseQuery)->where('form_type', 'par')->count();

        $issuances = (clone $baseQuery)
            ->with(['room', 'recipient', 'items'])
            ->orderByDesc('issued_at')
            ->paginate(15)
            ->withQueryString();

        return view('property_issuances.index', [
            'issuances' => $issuances,
            'rooms' => Room::orderBy('room_name')->get(),
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'roomId' => $roomId,
            'totalSlips' => $totalSlips,
            'totalIcs' => $totalIcs,
            'totalPar' => $totalPar,
        ]);
    }

    public function create(Request $request)
    {
        $room = null;

        if ($request->query('room_id')) {
            $room = Room::with(['propertyItems' => fn ($q) => $q->orderBy('property_name')])
                ->find($request->query('room_id'));
        }

        return view('property_issuances.create', [
            'room' => $room,
            'rooms' => Room::orderBy('room_name')->get(),
            'personnel' => Personnel::orderBy('fullname')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'nullable|exists:rooms,id',
            'existing_item_ids' => 'nullable|array',
            'existing_item_ids.*' => 'exists:property_items,id',
            'items' => 'nullable|array',
            // Left as nullable (not required_with) — a blank leftover row
            // is silently dropped below rather than hard-failing, since the
            // custodian may have only meant to use the Room checkboxes.
            'items.*.property_name' => 'nullable|string|max:255',
            'items.*.unit' => 'nullable|string|max:255',
            'items.*.property_number' => 'nullable|string|max:255',
            'items.*.quantity' => 'nullable|integer|min:1',
            'items.*.unit_cost' => 'nullable|numeric|min:0',
            'items.*.date_acquired' => 'nullable|date',
            'items.*.estimated_useful_life' => 'nullable|string|max:255',
            'recipient_personnel_id' => 'required|exists:personnel,id',
            'fund_cluster' => 'nullable|string|max:255',
            'po_number' => 'nullable|string|max:255',
            'issued_at' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        // Snapshots from existing Room items (checked boxes) and freeform
        // typed rows both feed the same items list — the real paper form
        // doesn't care where an item came from, it just lists what's issued.
        $lineItems = collect();

        if (!empty($validated['existing_item_ids'])) {
            $existing = PropertyItem::whereIn('id', $validated['existing_item_ids'])->get();

            foreach ($existing as $item) {
                $lineItems->push([
                    'property_item_id' => $item->id,
                    'property_name' => $item->property_name,
                    'property_number' => $item->property_number,
                    'unit' => $item->unit,
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->unit_value,
                    'date_acquired' => $item->date_acquired,
                    'estimated_useful_life' => $item->estimated_useful_life,
                ]);
            }
        }

        foreach ($validated['items'] ?? [] as $row) {

            if (empty($row['property_name'])) {
                continue;
            }

            $lineItems->push([
                'property_item_id' => null,
                'property_name' => $row['property_name'],
                'property_number' => $row['property_number'] ?? null,
                'unit' => $row['unit'] ?? null,
                'quantity' => $row['quantity'] ?? 1,
                'unit_cost' => $row['unit_cost'] ?? null,
                'date_acquired' => $row['date_acquired'] ?? null,
                'estimated_useful_life' => $row['estimated_useful_life'] ?? null,
            ]);
        }

        if ($lineItems->isEmpty()) {
            return back()->withInput()->with('error', 'Add at least one item to issue.');
        }

        $formTypes = $lineItems->map(fn ($row) => PropertyIssuance::determineFormType((float) ($row['unit_cost'] ?? 0)))->unique();

        // A real ICS/PAR is a single legal-value classification — mixing
        // items from different COA brackets on one slip would misstate the
        // form's own printed bracket label, so this is a hard stop rather
        // than a warning (unlike softer flags elsewhere in this app).
        if ($formTypes->count() > 1) {
            return back()->withInput()->with('error', 'Items span more than one value bracket (₱5,000 and below / ₱5,001–49,999.99 / ₱50,000 and above). Please issue them on separate slips.');
        }

        $formType = $formTypes->first();
        $slipNo = PropertyIssuance::generateSlipNo($formType);

        $issuance = DB::transaction(function () use ($validated, $formType, $slipNo, $lineItems) {

            $issuance = PropertyIssuance::create([
                'room_id' => $validated['room_id'] ?? null,
                'form_type' => $formType,
                'slip_no' => $slipNo,
                'fund_cluster' => $validated['fund_cluster'] ?? null,
                'po_number' => $validated['po_number'] ?? null,
                'recipient_personnel_id' => $validated['recipient_personnel_id'],
                'issued_by' => Auth::id(),
                'issued_at' => $validated['issued_at'],
                'remarks' => $validated['remarks'] ?? null,
                'created_by' => Auth::id(),
            ]);

            foreach ($lineItems as $row) {
                $issuance->items()->create($row);
            }

            return $issuance;
        });

        // 🔔 Notify the recipient (visible on their "My Property
        // Accountability" page) — only if they have a real login account;
        // some recipients on older/backfilled slips have none.
        $recipient = Personnel::find($validated['recipient_personnel_id']);

        if ($recipient && $recipient->user_id) {

            $notif = Notification::create([
                'user_id' => $recipient->user_id,
                'type' => 'property_issuance',
                'title' => 'New Property Endorsed to You',
                'url' => route('property-issuances.mine'),
                'message' =>
                    (Auth::user()->fullname ?? Auth::user()->username)
                    . ' endorsed property to you under slip ' . $slipNo . '.',
                'is_read' => 0,
            ]);

            event(new NewNotificationEvent($notif));
        }

        return redirect()
            ->route('property-issuances.show', $issuance->id)
            ->with('success', "Slip {$slipNo} generated.");
    }

    // Any personnel record's own accountability slips — self-service,
    // same pattern as My Schedule / My Job Requests.
    public function mine()
    {
        $personnel = Personnel::where('user_id', Auth::id())->first();

        abort_if(!$personnel, 403);

        $issuances = PropertyIssuance::where('recipient_personnel_id', $personnel->id)
            ->with(['room', 'items'])
            ->orderByDesc('issued_at')
            ->paginate(15);

        return view('property_issuances.mine', compact('issuances'));
    }

    public function show(PropertyIssuance $issuance)
    {
        $this->authorizeView($issuance);

        $issuance->load(['room', 'recipient.positionRecord', 'issuer', 'items', 'photos.uploader', 'photos.item']);

        return view('property_issuances.show', compact('issuance'));
    }

    public function print(PropertyIssuance $issuance)
    {
        $this->authorizeView($issuance);

        $issuance->load(['room', 'recipient.positionRecord', 'issuer.personnel.positionRecord', 'items', 'photos.uploader', 'photos.item']);

        return view('property_issuances.print', compact('issuance'));
    }

    // Property Custodian can view any slip; anyone else only their own
    // (as the recipient/point person named on it).
    private function authorizeView(PropertyIssuance $issuance): void
    {
        $user = Auth::user();

        if ($user->hasPermission('manage-property-issuance')) {
            return;
        }

        $personnel = Personnel::where('user_id', $user->id)->first();

        abort_if(!$personnel || $issuance->recipient_personnel_id !== $personnel->id, 403);
    }

    public function destroy(PropertyIssuance $issuance)
    {
        $issuance->delete();

        return redirect()
            ->route('property-issuances.index')
            ->with('success', 'Issuance slip deleted.');
    }

    public function uploadPhoto(Request $request, PropertyIssuance $issuance)
    {
        $validated = $request->validate([
            'property_issuance_item_id' => 'nullable|exists:property_issuance_items,id',
            'photos' => 'required|array',
            'photos.*' => 'image|max:5120',
        ], [
            'photos.required' => 'Please choose at least one photo to upload.',
            'photos.*.image' => 'Each file must be an image.',
            'photos.*.max' => 'Each photo must be 5MB or smaller.',
        ]);

        // Guard against a stale form submitting an item id from a
        // different issuance entirely.
        $itemId = $validated['property_issuance_item_id'] ?? null;
        if ($itemId && !$issuance->items()->where('id', $itemId)->exists()) {
            return back()->with('error', 'That item does not belong to this slip.');
        }

        foreach ($request->file('photos', []) as $photo) {

            $path = $photo->store('property_issuances', 'public');

            $issuance->photos()->create([
                'property_issuance_item_id' => $itemId,
                'path' => $path,
                'uploaded_by' => Auth::id(),
            ]);
        }

        return back()->with('success', 'Evidence photo(s) uploaded.');
    }

    public function destroyPhoto(PropertyIssuance $issuance, PropertyIssuancePhoto $photo)
    {
        abort_if($photo->property_issuance_id !== $issuance->id, 404);

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return back()->with('success', 'Photo removed.');
    }
}

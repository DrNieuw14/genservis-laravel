<?php

namespace App\Http\Controllers;

use App\Models\LetterTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LetterTrackingController extends Controller
{
    public function index(Request $request)
    {
        $direction = $request->query('direction');
        $status = $request->query('status');
        $search = $request->query('search');

        $letters = LetterTracking::query()
            ->when($direction, fn ($q) => $q->where('direction', $direction))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('control_no', 'like', "%{$search}%")
                    ->orWhere('from_office', 'like', "%{$search}%")
                    ->orWhere('to_office', 'like', "%{$search}%");
            }))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('letter_tracking.index', [
            'letters' => $letters,
            'direction' => $direction,
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateLetter($request);

        $latestId = LetterTracking::max('id') + 1;

        $validated['control_no'] = 'LTR-' . date('Y') . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);
        $validated['status'] = 'logged';
        $validated['created_by'] = Auth::id();

        LetterTracking::create($validated);

        return back()->with('success', 'Letter logged.');
    }

    public function update(Request $request, LetterTracking $letterTracking)
    {
        $letterTracking->update($this->validateLetter($request));

        return back()->with('success', 'Letter updated.');
    }

    // Moves the letter one step forward in the Logged -> For Signature ->
    // Signed -> Released pipeline, stamping today's date on the matching
    // milestone field.
    public function advance(LetterTracking $letterTracking)
    {
        $next = $letterTracking->nextStatus();

        if (!$next) {
            return back()->with('error', 'This letter has already been released.');
        }

        $dateField = [
            'for_signature' => 'forwarded_at',
            'signed' => 'signed_at',
            'released' => 'released_at',
        ][$next] ?? null;

        $letterTracking->update(array_filter([
            'status' => $next,
            $dateField => $dateField ? now() : null,
        ]));

        return back()->with('success', 'Letter marked as "' . $letterTracking->statusLabel() . '".');
    }

    public function destroy(LetterTracking $letterTracking)
    {
        $letterTracking->delete();

        return back()->with('success', 'Letter record removed.');
    }

    private function validateLetter(Request $request): array
    {
        return $request->validate([
            'direction' => 'required|in:incoming,outgoing',
            'subject' => 'required|string|max:255',
            'from_office' => 'nullable|string|max:150',
            'to_office' => 'nullable|string|max:150',
            'date_received' => 'nullable|date',
            'remarks' => 'nullable|string|max:2000',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ThesisAdvisee;
use App\Models\ThesisMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThesisMonitoringController extends Controller
{
    // Personal roster — each adviser only sees/manages their own advisees,
    // same "My Job Requests" style scoping used elsewhere in this app.
    private function authorizeOwner(ThesisAdvisee $advisee): void
    {
        if ($advisee->created_by !== Auth::id()) {
            abort(403);
        }
    }

    public function index()
    {
        if (! Auth::user()->hasPermission('manage-thesis-monitoring')) {
            abort(403);
        }

        $advisees = ThesisAdvisee::where('created_by', Auth::id())
            ->with(['movements', 'members'])
            ->get()
            ->sortByDesc(fn ($a) => $a->daysSinceLastMovement() ?? -1)
            ->values();

        return view('thesis_monitoring.index', compact('advisees'));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->hasPermission('manage-thesis-monitoring')) {
            abort(403);
        }

        $validated = $request->validate([
            'student_names' => 'required|array|min:1|max:6',
            'student_names.*' => 'required|string|max:255',
            'program' => 'nullable|string|max:100',
            'year_level' => 'nullable|string|max:50',
            'thesis_title' => 'required|string',
        ]);

        $advisee = ThesisAdvisee::create([
            'program' => $validated['program'] ?? null,
            'year_level' => $validated['year_level'] ?? null,
            'thesis_title' => $validated['thesis_title'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['student_names'] as $name) {
            $advisee->members()->create(['student_name' => $name]);
        }

        return back()->with('success', 'Advisee added.');
    }

    public function update(Request $request, ThesisAdvisee $thesisAdvisee)
    {
        if (! Auth::user()->hasPermission('manage-thesis-monitoring')) {
            abort(403);
        }

        $this->authorizeOwner($thesisAdvisee);

        $validated = $request->validate([
            'student_names' => 'required|array|min:1|max:6',
            'student_names.*' => 'required|string|max:255',
            'program' => 'nullable|string|max:100',
            'year_level' => 'nullable|string|max:50',
            'thesis_title' => 'required|string',
        ]);

        $thesisAdvisee->update([
            'program' => $validated['program'] ?? null,
            'year_level' => $validated['year_level'] ?? null,
            'thesis_title' => $validated['thesis_title'],
        ]);

        // Member list has no history to preserve (unlike the movement log),
        // so a full replace on every edit is simplest and safe.
        $thesisAdvisee->members()->delete();
        foreach ($validated['student_names'] as $name) {
            $thesisAdvisee->members()->create(['student_name' => $name]);
        }

        return back()->with('success', 'Advisee updated.');
    }

    public function destroy(ThesisAdvisee $thesisAdvisee)
    {
        if (! Auth::user()->hasPermission('manage-thesis-monitoring')) {
            abort(403);
        }

        $this->authorizeOwner($thesisAdvisee);

        $thesisAdvisee->delete();

        return redirect()->route('thesis-monitoring.index')->with('success', 'Advisee removed.');
    }

    public function show(ThesisAdvisee $thesisAdvisee)
    {
        if (! Auth::user()->hasPermission('manage-thesis-monitoring')) {
            abort(403);
        }

        $this->authorizeOwner($thesisAdvisee);

        $thesisAdvisee->load(['movements.loggedBy', 'members']);

        // Standard milestones plus whatever custom stage names this adviser
        // has already typed before (across all their advisees) — same
        // "suggest, don't force" pattern as the Class Scheduling Room field.
        $standardStages = [
            'Outline', 'Chapter 1', 'Chapter 2', 'Chapter 3',
            'Chapter 4', 'Chapter 5', 'Final Manuscript',
        ];

        $usedStages = ThesisMovement::whereHas(
            'advisee', fn ($q) => $q->where('created_by', Auth::id())
        )->distinct()->pluck('chapter_stage');

        $chapterStages = collect($standardStages)->merge($usedStages)->unique()->values();

        return view('thesis_monitoring.show', compact('thesisAdvisee', 'chapterStages'));
    }

    public function storeMovement(Request $request, ThesisAdvisee $thesisAdvisee)
    {
        if (! Auth::user()->hasPermission('manage-thesis-monitoring')) {
            abort(403);
        }

        $this->authorizeOwner($thesisAdvisee);

        $validated = $request->validate([
            'direction' => 'required|in:in,out',
            'chapter_stage' => 'required|string|max:100',
            'moved_at' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $thesisAdvisee->movements()->create($validated + ['logged_by' => Auth::id()]);

        return back()->with('success', 'Movement logged.');
    }

    public function destroyMovement(ThesisAdvisee $thesisAdvisee, ThesisMovement $movement)
    {
        if (! Auth::user()->hasPermission('manage-thesis-monitoring')) {
            abort(403);
        }

        $this->authorizeOwner($thesisAdvisee);

        if ($movement->thesis_advisee_id !== $thesisAdvisee->id) {
            abort(404);
        }

        $movement->delete();

        return back()->with('success', 'Movement entry removed.');
    }
}

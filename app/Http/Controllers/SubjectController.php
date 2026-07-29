<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $query = Subject::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%');
            });
        }

        $subjects = $query->orderBy('code')->paginate(20)->withQueryString();

        return view('class_schedule.subjects.index', compact('subjects'));
    }

    public function create()
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        return view('class_schedule.subjects.create');
    }

    public function store(Request $request)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:30|unique:subjects,code',
            'title' => 'nullable|string|max:255',
            'lecture_units' => 'nullable|numeric|min:0',
            'lab_units' => 'nullable|numeric|min:0',
        ]);

        Subject::create($validated);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject added.');
    }

    public function edit(Subject $subject)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        return view('class_schedule.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:30|unique:subjects,code,' . $subject->id,
            'title' => 'nullable|string|max:255',
            'lecture_units' => 'nullable|numeric|min:0',
            'lab_units' => 'nullable|numeric|min:0',
        ]);

        $subject->update($validated);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject updated.');
    }

    public function destroy(Subject $subject)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        if ($subject->classSchedules()->exists()) {
            return back()->with('error', 'Cannot delete a subject that is used in the schedule.');
        }

        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject deleted.');
    }
}

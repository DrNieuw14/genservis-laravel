<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $query = Section::with('program');

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->filled('school_year')) {
            $query->where('school_year', $request->school_year);
        }

        $sections = $query->orderBy('program_id')->orderBy('year_level')->orderBy('section_letter')->get();
        $programs = Program::orderBy('code')->get();

        return view('class_schedule.sections.index', compact('sections', 'programs'));
    }

    public function create()
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $programs = Program::where('is_active', true)->orderBy('code')->get();

        return view('class_schedule.sections.create', compact('programs'));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'year_level' => 'required|integer|min:1|max:6',
            'section_letter' => 'required|string|max:5',
            'school_year' => 'required|string|max:20',
            'semester' => 'required|in:1st Semester,2nd Semester,Summer',
            'number_of_students' => 'nullable|integer|min:0',
        ]);

        Section::create($validated);

        return redirect()
            ->route('sections.index')
            ->with('success', 'Section added.');
    }

    public function edit(Section $section)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $programs = Program::orderBy('code')->get();

        return view('class_schedule.sections.edit', compact('section', 'programs'));
    }

    public function update(Request $request, Section $section)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'year_level' => 'required|integer|min:1|max:6',
            'section_letter' => 'required|string|max:5',
            'school_year' => 'required|string|max:20',
            'semester' => 'required|in:1st Semester,2nd Semester,Summer',
            'number_of_students' => 'nullable|integer|min:0',
        ]);

        $section->update($validated);

        return redirect()
            ->route('sections.index')
            ->with('success', 'Section updated.');
    }

    public function destroy(Section $section)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        if ($section->classSchedules()->exists()) {
            return back()->with('error', 'Cannot delete a section that has schedule entries. Remove them first.');
        }

        $section->delete();

        return redirect()
            ->route('sections.index')
            ->with('success', 'Section deleted.');
    }
}

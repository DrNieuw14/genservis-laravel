<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    public function index()
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $programs = Program::with('department')->orderBy('code')->get();

        return view('class_schedule.programs.index', compact('programs'));
    }

    public function create()
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $departments = Department::orderBy('department_name')->get();

        return view('class_schedule.programs.create', compact('departments'));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:programs,code',
            'title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        Program::create($validated);

        return redirect()
            ->route('programs.index')
            ->with('success', 'Program added.');
    }

    public function edit(Program $program)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $departments = Department::orderBy('department_name')->get();

        return view('class_schedule.programs.edit', compact('program', 'departments'));
    }

    public function update(Request $request, Program $program)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:programs,code,' . $program->id,
            'title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $program->update($validated);

        return redirect()
            ->route('programs.index')
            ->with('success', 'Program updated.');
    }

    public function destroy(Program $program)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        if ($program->sections()->exists()) {
            return back()->with('error', 'Cannot delete a program that has sections. Remove its sections first.');
        }

        $program->delete();

        return redirect()
            ->route('programs.index')
            ->with('success', 'Program deleted.');
    }
}

@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800">✎ Edit Section</h2>
        <x-back-button :href="route('sections.index')" />
    </div>

    <form method="POST" action="{{ route('sections.update', $section->id) }}" class="max-w-xl space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
            <select name="program_id" class="w-full border rounded px-4 py-2" required>
                @foreach($programs as $p)
                <option value="{{ $p->id }}" @selected(old('program_id', $section->program_id) == $p->id)>{{ $p->code }} — {{ $p->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                <input type="number" name="year_level" min="1" max="6" value="{{ old('year_level', $section->year_level) }}" class="w-full border rounded px-4 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Section Letter</label>
                <input type="text" name="section_letter" value="{{ old('section_letter', $section->section_letter) }}" class="w-full border rounded px-4 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">School Year</label>
                <input type="text" name="school_year" value="{{ old('school_year', $section->school_year) }}" class="w-full border rounded px-4 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                <select name="semester" class="w-full border rounded px-4 py-2" required>
                    @foreach(['1st Semester', '2nd Semester', 'Summer'] as $sem)
                    <option value="{{ $sem }}" @selected(old('semester', $section->semester) === $sem)>{{ $sem }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Number of Students</label>
            <input type="number" name="number_of_students" min="0" value="{{ old('number_of_students', $section->number_of_students) }}" class="w-full border rounded px-4 py-2">
        </div>

        <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-6 py-3 rounded-xl shadow-lg font-semibold">Save</button>

    </form>

</div>

@endsection

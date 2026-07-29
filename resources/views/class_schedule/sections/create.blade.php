@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800">➕ Add Section</h2>
        <x-back-button :href="route('sections.index')" />
    </div>

    <form method="POST" action="{{ route('sections.store') }}" class="max-w-xl space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
            <select name="program_id" class="w-full border rounded px-4 py-2" required>
                <option value="">-- Select Program --</option>
                @foreach($programs as $p)
                <option value="{{ $p->id }}" @selected(old('program_id') == $p->id)>{{ $p->code }} — {{ $p->title }}</option>
                @endforeach
            </select>
            @error('program_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                <input type="number" name="year_level" min="1" max="6" value="{{ old('year_level', 1) }}" class="w-full border rounded px-4 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Section Letter</label>
                <input type="text" name="section_letter" value="{{ old('section_letter') }}" placeholder="A" class="w-full border rounded px-4 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">School Year</label>
                <input type="text" name="school_year" value="{{ old('school_year', '2026-2027') }}" class="w-full border rounded px-4 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                <select name="semester" class="w-full border rounded px-4 py-2" required>
                    <option value="1st Semester" @selected(old('semester') === '1st Semester')>1st Semester</option>
                    <option value="2nd Semester" @selected(old('semester') === '2nd Semester')>2nd Semester</option>
                    <option value="Summer" @selected(old('semester') === 'Summer')>Summer</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Number of Students (optional)</label>
            <input type="number" name="number_of_students" min="0" value="{{ old('number_of_students') }}" class="w-full border rounded px-4 py-2">
        </div>

        <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-6 py-3 rounded-xl shadow-lg font-semibold">Save</button>

    </form>

</div>

@endsection

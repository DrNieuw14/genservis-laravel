@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800">➕ Add Subject</h2>
        <x-back-button :href="route('subjects.index')" />
    </div>

    <form method="POST" action="{{ route('subjects.store') }}" class="max-w-xl space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Code (e.g. DCIT 22)</label>
            <input type="text" name="code" value="{{ old('code') }}" class="w-full border rounded px-4 py-2" required>
            @error('code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title (optional)</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-4 py-2">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lecture Units</label>
                <input type="number" step="0.5" min="0" name="lecture_units" value="{{ old('lecture_units', 0) }}" class="w-full border rounded px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lab Units</label>
                <input type="number" step="0.5" min="0" name="lab_units" value="{{ old('lab_units', 0) }}" class="w-full border rounded px-4 py-2">
            </div>
        </div>

        <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-6 py-3 rounded-xl shadow-lg font-semibold">Save</button>

    </form>

</div>

@endsection

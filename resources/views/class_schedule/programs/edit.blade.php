@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800">✎ Edit Program</h2>
        <x-back-button :href="route('programs.index')" />
    </div>

    <form method="POST" action="{{ route('programs.update', $program->id) }}" class="max-w-xl space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
            <input type="text" name="code" value="{{ old('code', $program->code) }}" class="w-full border rounded px-4 py-2" required>
            @error('code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $program->title) }}" class="w-full border rounded px-4 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
            <select name="department_id" class="w-full border rounded px-4 py-2">
                <option value="">-- None --</option>
                @foreach($departments as $d)
                <option value="{{ $d->id }}" @selected(old('department_id', $program->department_id) == $d->id)>{{ $d->department_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" id="isActive" @checked(old('is_active', $program->is_active)) class="rounded">
            <label for="isActive" class="text-sm font-medium text-gray-700">Active</label>
        </div>

        <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-6 py-3 rounded-xl shadow-lg font-semibold">Save</button>

    </form>

</div>

@endsection

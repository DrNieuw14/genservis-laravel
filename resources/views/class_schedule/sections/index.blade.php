@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800">📚 Sections</h2>
        <div class="flex gap-2">
            <a href="{{ route('sections.create') }}" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-5 py-3 rounded-xl shadow-lg font-semibold">➕ Add Section</a>
            <x-back-button :href="route('class-schedule.index')" />
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6 text-lg">{{ session('error') }}</div>
    @endif

    <form method="GET" class="mb-6">
        <select name="program_id" class="border rounded px-4 py-2" onchange="this.form.submit()">
            <option value="">All Programs</option>
            @foreach($programs as $p)
            <option value="{{ $p->id }}" @selected(request('program_id') == $p->id)>{{ $p->code }}</option>
            @endforeach
        </select>
    </form>

    <table class="min-w-full">
        <thead>
            <tr class="border-b bg-gray-100">
                <th class="text-left px-4 py-3">Section</th>
                <th class="text-left px-4 py-3">School Year</th>
                <th class="text-left px-4 py-3">Semester</th>
                <th class="text-left px-4 py-3">No. of Students</th>
                <th class="text-center px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sections as $s)
            <tr class="border-b">
                <td class="px-4 py-3 font-semibold">{{ $s->label }}</td>
                <td class="px-4 py-3">{{ $s->school_year }}</td>
                <td class="px-4 py-3">{{ $s->semester }}</td>
                <td class="px-4 py-3">{{ $s->number_of_students ?? '—' }}</td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('class-schedule.index', ['section_id' => $s->id]) }}" class="text-green-600 hover:underline">Schedule</a>
                    |
                    <a href="{{ route('sections.edit', $s->id) }}" class="text-blue-600 hover:underline">Edit</a>
                    |
                    <form action="{{ route('sections.destroy', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this section?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-8 text-gray-500">No sections yet</td></tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection

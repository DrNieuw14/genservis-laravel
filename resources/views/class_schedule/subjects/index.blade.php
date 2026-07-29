@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800">📖 Subjects</h2>
        <div class="flex gap-2">
            <a href="{{ route('subjects.create') }}" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-5 py-3 rounded-xl shadow-lg font-semibold">➕ Add Subject</a>
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
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code or title..." class="border rounded px-4 py-2 w-full md:w-96">
    </form>

    <table class="min-w-full">
        <thead>
            <tr class="border-b bg-gray-100">
                <th class="text-left px-4 py-3">Code</th>
                <th class="text-left px-4 py-3">Title</th>
                <th class="text-right px-4 py-3">Lecture</th>
                <th class="text-right px-4 py-3">Lab</th>
                <th class="text-center px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subjects as $s)
            <tr class="border-b">
                <td class="px-4 py-3 font-semibold">{{ $s->code }}</td>
                <td class="px-4 py-3">{{ $s->title ?? '—' }}</td>
                <td class="px-4 py-3 text-right">{{ $s->lecture_units }}</td>
                <td class="px-4 py-3 text-right">{{ $s->lab_units }}</td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('subjects.edit', $s->id) }}" class="text-blue-600 hover:underline">Edit</a>
                    |
                    <form action="{{ route('subjects.destroy', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this subject?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-8 text-gray-500">No subjects yet</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">{{ $subjects->links() }}</div>

</div>

@endsection

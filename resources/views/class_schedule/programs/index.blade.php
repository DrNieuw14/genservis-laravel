@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">🎓 Programs</h2>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('programs.create') }}" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-5 py-3 rounded-xl shadow-lg font-semibold">➕ Add Program</a>
            <x-back-button :href="route('class-schedule.index')" />
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6 text-lg">{{ session('error') }}</div>
    @endif

    <table class="min-w-full">
        <thead>
            <tr class="border-b bg-gray-100">
                <th class="text-left px-4 py-3">Code</th>
                <th class="text-left px-4 py-3">Title</th>
                <th class="text-left px-4 py-3">Department</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-center px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($programs as $p)
            <tr class="border-b">
                <td class="px-4 py-3 font-semibold">{{ $p->code }}</td>
                <td class="px-4 py-3">{{ $p->title }}</td>
                <td class="px-4 py-3">{{ $p->department->department_name ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $p->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $p->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('programs.edit', $p->id) }}" class="text-blue-600 hover:underline">Edit</a>
                    |
                    <form action="{{ route('programs.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this program?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-8 text-gray-500">No programs yet</td></tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection

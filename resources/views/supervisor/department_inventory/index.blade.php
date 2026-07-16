@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto mt-8">

    <h2 class="text-3xl font-bold text-white mb-6">
        🏢 Department Inventory
    </h2>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        <div class="rounded-2xl shadow-xl p-5 bg-blue-600 text-white">
            <h3 class="text-sm">Total Releases</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalReleases }}</p>
        </div>

        <div class="rounded-2xl shadow-xl p-5 bg-green-600 text-white">
            <h3 class="text-sm">Total Quantity Released</h3>
            <p class="text-3xl font-bold mt-2">{{ number_format($totalQuantityReleased) }}</p>
        </div>

        <div class="rounded-2xl shadow-xl p-5 bg-purple-600 text-white">
            <h3 class="text-sm">Departments Involved</h3>
            <p class="text-3xl font-bold mt-2">{{ $departmentsInvolved }}</p>
        </div>

        <div class="rounded-2xl shadow-xl p-5 bg-indigo-600 text-white">
            <h3 class="text-sm">Materials Involved</h3>
            <p class="text-3xl font-bold mt-2">{{ $materialsInvolved }}</p>
        </div>

    </div>

    <!-- FILTERS -->
    <div class="bg-white rounded-2xl shadow-xl p-4 mb-6">

        <form method="GET" action="{{ route('department.inventory') }}">

            <div class="flex flex-col md:flex-row gap-3">

                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by material name..."
                    class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

                <select name="department_id"
                        class="border rounded-xl p-3 focus:ring-2 focus:ring-purple-400">

                    <option value="">All Departments</option>

                    @foreach($departments as $department)

                        <option value="{{ $department->id }}"
                            {{ request('department_id') == $department->id ? 'selected' : '' }}>

                            {{ $department->department_name }}

                        </option>

                    @endforeach

                </select>

                <button class="bg-blue-600 text-white px-6 rounded-xl hover:bg-blue-700 transition">
                    Filter
                </button>

            </div>

        </form>

    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-blue-600 text-white">

                <tr>

                    <th class="p-4 text-left">Department</th>

                    <th class="p-4 text-left">Material</th>

                    <th class="p-4 text-left">Quantity</th>

                    <th class="p-4 text-left">Released By</th>

                    <th class="p-4 text-left">Released At</th>

                </tr>

            </thead>

            <tbody>

                @forelse($inventories as $item)

                <tr class="border-b hover:bg-gray-50 transition">

                    <td class="p-4">
                        {{ $item->department?->department_name ?? 'Unknown Department' }}
                    </td>

                    <td class="p-4">
                        {{ $item->material?->name ?? 'Deleted Material' }}
                    </td>

                    <td class="p-4">
                        {{ $item->quantity }}
                    </td>

                    <td class="p-4">
                        {{ $item->releaser?->fullname ?? $item->releaser?->username ?? 'Unknown User' }}
                    </td>

                    <td class="p-4">
                        {{ $item->released_at ? \Carbon\Carbon::parse($item->released_at)->format('M d, Y h:i A') : '-' }}
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center text-gray-500 py-10">
                        No department releases found.
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

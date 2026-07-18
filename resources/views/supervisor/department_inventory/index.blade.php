@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3 mb-6">
        🏢 Department Inventory
    </h2>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        <div class="rounded-xl shadow-lg p-5 bg-blue-600 text-white">
            <div class="text-base">Total Releases</div>
            <div class="text-4xl font-bold">{{ $totalReleases }}</div>
        </div>

        <div class="rounded-xl shadow-lg p-5 bg-green-600 text-white">
            <div class="text-base">Total Quantity Released</div>
            <div class="text-4xl font-bold">{{ number_format($totalQuantityReleased) }}</div>
        </div>

        <div class="rounded-xl shadow-lg p-5 bg-purple-600 text-white">
            <div class="text-base">Departments Involved</div>
            <div class="text-4xl font-bold">{{ $departmentsInvolved }}</div>
        </div>

        <div class="rounded-xl shadow-lg p-5 bg-indigo-600 text-white">
            <div class="text-base">Materials Involved</div>
            <div class="text-4xl font-bold">{{ $materialsInvolved }}</div>
        </div>

    </div>

    <!-- FILTERS -->
    <div class="border rounded-lg p-4 bg-gray-50 mb-6">

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

    <div class="border rounded-lg overflow-hidden">

        <table class="w-full text-lg">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-4 text-left text-gray-800">Department</th>

                    <th class="p-4 text-left text-gray-800">Material</th>

                    <th class="p-4 text-left text-gray-800">Quantity</th>

                    <th class="p-4 text-left text-gray-800">Released By</th>

                    <th class="p-4 text-left text-gray-800">Released At</th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($inventories as $item)

                <tr class="hover:bg-gray-50 transition">

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

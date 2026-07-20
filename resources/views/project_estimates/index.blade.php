@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧾 Project Detailed Estimates
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Cost estimates for repair/rehabilitation projects.
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('project-estimates.reports') }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                📊 Report
            </a>

            <a href="{{ route('project-estimates.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow">
                ➕ New Estimate
            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('project-estimates.index') }}" class="mb-6">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by project name or reference no."
            class="w-full border rounded-lg p-3">
    </form>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reference No.</th>
                    <th class="p-3 text-left">Project Name</th>
                    <th class="p-3 text-left">Location</th>
                    <th class="p-3">Items</th>
                    <th class="p-3">Estimated Total</th>
                    <th class="p-3">Created</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($estimates as $estimate)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3">{{ $estimate->reference_no }}</td>

                        <td class="p-3 font-semibold">{{ $estimate->project_name }}</td>

                        <td class="p-3">{{ $estimate->location ?? '-' }}</td>

                        <td class="p-3 text-center">{{ $estimate->items->count() }}</td>

                        <td class="p-3 text-center">₱{{ number_format($estimate->grandTotal(), 2) }}</td>

                        <td class="p-3 text-center">{{ $estimate->created_at->format('M d, Y') }}</td>

                        <td class="p-3 text-center">
                            <a href="{{ route('project-estimates.show', $estimate->id) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                View
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No project estimates yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $estimates->links() }}
    </div>

</div>

@endsection

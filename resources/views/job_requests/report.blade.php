@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex justify-between items-start mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📊 Job Request Report
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Completed job requests, for your own reporting and records.
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="route('job-requests.index')" />

            <a href="{{ route('job-requests.reports.print', request()->query()) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

        </div>

    </div>

    <!-- FILTERS -->
    <form method="GET" action="{{ route('job-requests.reports') }}" class="border rounded-lg p-5 bg-gray-50 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            <div>
                <label class="block mb-1 font-semibold text-sm">From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-sm">To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-sm">Category</label>
                <select name="category" class="w-full border rounded-lg p-3">
                    <option value="">All Categories</option>
                    <option value="physical_plant" {{ $category === 'physical_plant' ? 'selected' : '' }}>Physical Plant & Services</option>
                    <option value="utility" {{ $category === 'utility' ? 'selected' : '' }}>Utility Personnel</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                    🔍 Filter
                </button>

                @if($dateFrom || $dateTo || $category)
                    <a href="{{ route('job-requests.reports') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg shadow">
                        Clear
                    </a>
                @endif
            </div>

        </div>

    </form>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-green-100">Completed Jobs</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $totalCompleted }}</h2>
                </div>
                <div class="text-5xl opacity-70">🏁</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-blue-100">Physical Plant</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $physicalPlantCount }}</h2>
                </div>
                <div class="text-5xl opacity-70">🏗️</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-purple-100">Utility</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $utilityCount }}</h2>
                </div>
                <div class="text-5xl opacity-70">🧹</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-teal-100">Evidence Photos</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $totalPhotos }}</h2>
                </div>
                <div class="text-5xl opacity-70">📸</div>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reference No.</th>
                    <th class="p-3 text-left">Requesting Party</th>
                    <th class="p-3 text-left">Nature of Request</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Assigned To</th>
                    <th class="p-3">Approved By</th>
                    <th class="p-3">Date Completed</th>
                    <th class="p-3">Evidence</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($completedJobs as $job)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3">{{ $job->reference_no }}</td>

                        <td class="p-3">
                            {{ $job->requesting_party }}
                            <div class="text-sm text-gray-500">{{ $job->department->department_name ?? '-' }}</div>
                        </td>

                        <td class="p-3">{{ $job->nature_of_request }}</td>

                        <td class="p-3 text-center">{{ $job->categoryLabel() }}</td>

                        <td class="p-3 text-center">
                            @forelse($job->assignedPersonnel as $person)
                                <div>{{ $person->fullname }}</div>
                            @empty
                                <span class="text-gray-400">-</span>
                            @endforelse
                        </td>

                        <td class="p-3 text-center">
                            {{ $job->approver->fullname ?? $job->approver->name ?? '-' }}
                        </td>

                        <td class="p-3 text-center">
                            {{ $job->completed_at?->format('M d, Y') ?? '-' }}
                        </td>

                        <td class="p-3 text-center">
                            @if($job->photos->isNotEmpty())
                                📸 {{ $job->photos->count() }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="p-3 text-center">
                            <a href="{{ route('job-requests.show', $job->id) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                View
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="p-6 text-center text-gray-500">
                            No completed job requests match these filters.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

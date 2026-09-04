@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🔍 Problem Reports
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Campus issues reported by staff — review, assess, and convert to a Job Request when needed.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('error') }}</div>
    @endif

    <!-- FILTER -->
    <form method="GET" action="{{ route('problem-reports.index') }}" class="border rounded-lg p-5 bg-gray-50 mb-6">

        <div class="flex flex-wrap items-end gap-4">

            <div>
                <label class="block mb-1 font-semibold text-sm">Status</label>
                <select name="status" class="border rounded-lg p-3">
                    <option value="">All</option>
                    @foreach(\App\Models\ProblemReport::STATUSES as $key => $label)
                        <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                🔍 Filter
            </button>

            @if($status)
                <a href="{{ route('problem-reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg shadow">
                    Clear
                </a>
            @endif

        </div>

    </form>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reference No.</th>
                    <th class="p-3 text-left">Reported By</th>
                    <th class="p-3 text-left">Location</th>
                    <th class="p-3 text-left">Problem</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Reported</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($reports as $report)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $report->reference_no }}</td>
                        <td class="p-3">{{ $report->reporter->fullname ?? $report->reporter->name ?? '-' }}</td>
                        <td class="p-3">{{ $report->location }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($report->problem_description, 50) }}</td>
                        <td class="p-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold
                                {{ $report->status === 'converted' ? 'bg-blue-100 text-blue-700' :
                                   ($report->status === 'resolved' ? 'bg-green-100 text-green-700' :
                                   ($report->status === 'dismissed' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-100 text-yellow-700')) }}">
                                {{ $report->statusLabel() }}
                            </span>
                        </td>
                        <td class="p-3 text-center text-sm text-gray-600">{{ $report->created_at->format('M d, Y') }}</td>
                        <td class="p-3 text-center">
                            <a href="{{ route('problem-reports.show', $report->id) }}" class="text-blue-600 hover:underline text-sm">
                                📋 Review
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No problem reports yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $reports->links() }}
    </div>

</div>

@endsection

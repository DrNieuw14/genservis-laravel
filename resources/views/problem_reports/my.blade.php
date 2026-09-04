@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📜 My Problem Reports
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Track the campus issues you've reported.
            </p>
        </div>

        <a href="{{ route('problem-reports.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            Report a Problem
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reference No.</th>
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
                        <td class="p-3">{{ $report->location }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($report->problem_description, 60) }}</td>
                        <td class="p-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold
                                {{ $report->status === 'converted' ? 'bg-blue-100 text-blue-700' :
                                   ($report->status === 'resolved' ? 'bg-green-100 text-green-700' :
                                   ($report->status === 'dismissed' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-100 text-yellow-700')) }}">
                                {{ $report->statusLabel() }}
                            </span>
                            @if($report->jobRequest)
                                <p class="text-xs text-gray-400 mt-1">{{ $report->jobRequest->reference_no }}</p>
                            @endif
                        </td>
                        <td class="p-3 text-center text-sm text-gray-600">{{ $report->created_at->format('M d, Y') }}</td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('problem-reports.show', $report->id) }}" class="text-blue-600 hover:underline text-sm">
                                    📋 View
                                </a>

                                @if($report->status === 'reported')
                                    <a href="{{ route('problem-reports.edit', $report->id) }}" class="text-gray-600 hover:underline text-sm">
                                        ✏️ Edit
                                    </a>

                                    <form method="POST" action="{{ route('problem-reports.destroy', $report->id) }}"
                                          onsubmit="return genservisConfirm(event, 'Delete this problem report? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">
                                            🗑 Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            You haven't reported any problems yet.
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

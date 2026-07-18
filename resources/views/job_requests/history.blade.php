@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📜 My Job Requests
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Track the status of the job requests you've submitted.
            </p>
        </div>

        <a href="{{ route('job-requests.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            🛠️ New Job Request
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reference No.</th>
                    <th class="p-3 text-left">Nature of Request</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Target Date</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($requests as $request)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3">{{ $request->reference_no }}</td>

                        <td class="p-3">{{ $request->nature_of_request }}</td>

                        <td class="p-3 text-center">{{ $request->categoryLabel() }}</td>

                        <td class="p-3 text-center">
                            {{ $request->target_date?->format('M d, Y') ?? '-' }}
                        </td>

                        <td class="p-3 text-center">
                            @include('job_requests.partials.status-badge', ['status' => $request->status])
                        </td>

                        <td class="p-3 text-center">
                            <a href="{{ route('job-requests.show', $request->id) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                View
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            You haven't submitted any job requests yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

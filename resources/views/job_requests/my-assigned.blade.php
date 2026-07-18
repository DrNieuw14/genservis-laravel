@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            🔧 My Assigned Jobs
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Job requests you've been assigned to work on.
        </p>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reference No.</th>
                    <th class="p-3 text-left">Nature of Request</th>
                    <th class="p-3">Location</th>
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

                        <td class="p-3 text-center">{{ $request->office_unit_project }}</td>

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
                            You haven't been assigned to any job requests yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

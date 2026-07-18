@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🛠️ Job Request Approvals
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Review, approve, and track job requests routed to you.
            </p>
        </div>

        @if($pendingCount > 0)
            <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-semibold">
                ⏳ {{ $pendingCount }} pending on this page
            </span>
        @endif
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
                    <th class="p-3 text-left">Requesting Party</th>
                    <th class="p-3 text-left">Nature of Request</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Assigned To</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($requests as $request)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3">{{ $request->reference_no }}</td>

                        <td class="p-3">
                            {{ $request->requesting_party }}
                            <div class="text-sm text-gray-500">{{ $request->department->department_name ?? '-' }}</div>
                        </td>

                        <td class="p-3">{{ $request->nature_of_request }}</td>

                        <td class="p-3 text-center">{{ $request->categoryLabel() }}</td>

                        <td class="p-3 text-center">
                            @forelse($request->assignedPersonnel as $person)
                                <div>{{ $person->fullname }}</div>
                            @empty
                                <span class="text-gray-400">-</span>
                            @endforelse
                        </td>

                        <td class="p-3 text-center">
                            @include('job_requests.partials.status-badge', ['status' => $request->status])
                            @if($request->photos->isNotEmpty())
                                <div class="text-xs text-gray-500 mt-1">
                                    📸 {{ $request->photos->count() }} photo{{ $request->photos->count() > 1 ? 's' : '' }}
                                </div>
                            @endif
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
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No job requests on record yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>

</div>

@endsection

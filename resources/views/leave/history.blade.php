@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📄 My Leave Requests
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Track your submitted leave requests and approval status.
            </p>
        </div>

        <a href="{{ route('leave.index') }}"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            📝 Apply for Leave
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="bg-yellow-500 text-white p-5 rounded-xl shadow-lg">
            <div class="text-base">Pending</div>
            <div class="text-4xl font-bold">
                {{ $leaves->where('status','Pending')->count() }}
            </div>
        </div>

        <div class="bg-green-500 text-white p-5 rounded-xl shadow-lg">
            <div class="text-base">Approved</div>
            <div class="text-4xl font-bold">
                {{ $leaves->where('status','Approved')->count() }}
            </div>
        </div>

        <div class="bg-red-500 text-white p-5 rounded-xl shadow-lg">
            <div class="text-base">Rejected</div>
            <div class="text-4xl font-bold">
                {{ $leaves->where('status','Rejected')->count() }}
            </div>
        </div>

    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 mb-4 rounded shadow text-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABLE -->
    <div class="border rounded-lg overflow-hidden">
        <table class="w-full text-lg">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left text-gray-800">Type</th>
                    <th class="p-3 text-left text-gray-800">Reason</th>
                    <th class="p-3 text-left text-gray-800">Start Date</th>
                    <th class="p-3 text-left text-gray-800">End Date</th>
                    <th class="p-3 text-left text-gray-800">Status</th>
                    <th class="p-3 text-left text-gray-800">Submitted</th>
                    <th class="p-3 text-left text-gray-800">Approved Date</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse($leaves as $leave)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="p-3">
                            {{ $leave->leave_type }}
                        </td>

                        <td class="p-3">
                            {{ $leave->reason }}
                        </td>

                        <td class="p-3">
                            {{ $leave->start_date->format('M d, Y') }}
                        </td>

                        <td class="p-3">
                            {{ $leave->end_date->format('M d, Y') }}
                        </td>

                        <td class="p-4">

                            @if($leave->status == 'Pending')

                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    ⏳ Pending
                                </span>

                            @elseif($leave->status == 'Approved')

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    ✅ Approved
                                </span>

                            @else

                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    ❌ Rejected
                                </span>

                                @if($leave->rejection_reason)
                                    <div class="text-xs text-gray-500 mt-1 italic">"{{ $leave->rejection_reason }}"</div>
                                @endif

                            @endif

                        </td>

                        <td class="p-3">
                            {{ $leave->created_at->format('M d, Y') }}
                        </td>

                        <td class="p-3">
                            {{ $leave->approved_at ? \Carbon\Carbon::parse($leave->approved_at)->format('M d, Y h:i A') : '-' }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">
                            No leave requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>


@endsection
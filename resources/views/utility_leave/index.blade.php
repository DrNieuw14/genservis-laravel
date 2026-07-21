@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📄 Utility Leave Requests
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Leave requests from Utility & Maintenance Staff.
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('utility-leave.reports') }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                📊 Report
            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

        <div class="bg-yellow-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Pending</div>
            <div class="text-4xl font-bold">{{ $leaves->where('status','Pending')->count() }}</div>
        </div>

        <div class="bg-green-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">✅ Approved</div>
            <div class="text-4xl font-bold">{{ $leaves->where('status','Approved')->count() }}</div>
        </div>

        <div class="bg-red-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Rejected</div>
            <div class="text-4xl font-bold">{{ $leaves->where('status','Rejected')->count() }}</div>
        </div>

        <div class="bg-blue-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Total Requests</div>
            <div class="text-4xl font-bold">{{ $leaves->count() }}</div>
        </div>

    </div>

    <!-- FILTER -->
    <div class="border rounded-lg p-4 bg-gray-50 mb-6">

        <div class="flex flex-wrap gap-2 mb-4">

            <a href="{{ route('utility-leave.index') }}"
            class="px-4 py-2 rounded-xl font-semibold
            {{ !$status ? 'bg-blue-600 text-white' : 'bg-gray-100' }}">
                All
            </a>

            <a href="{{ route('utility-leave.index', ['status' => 'Pending']) }}"
            class="px-4 py-2 rounded-xl font-semibold
            {{ $status == 'Pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100' }}">
                Pending
            </a>

            <a href="{{ route('utility-leave.index', ['status' => 'Approved']) }}"
            class="px-4 py-2 rounded-xl font-semibold
            {{ $status == 'Approved' ? 'bg-green-600 text-white' : 'bg-gray-100' }}">
                Approved
            </a>

            <a href="{{ route('utility-leave.index', ['status' => 'Rejected']) }}"
            class="px-4 py-2 rounded-xl font-semibold
            {{ $status == 'Rejected' ? 'bg-red-600 text-white' : 'bg-gray-100' }}">
                Rejected
            </a>

        </div>

        <form method="GET" action="{{ route('utility-leave.index') }}">
            <div class="flex gap-3">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search staff..."
                    class="w-full border rounded-xl p-3">
                <input type="hidden" name="status" value="{{ $status }}">
                <button class="bg-blue-600 text-white px-6 rounded-xl hover:bg-blue-700">
                    Search
                </button>
            </div>
        </form>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full text-lg">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left">Staff</th>
                    <th class="p-4 text-left">Type</th>
                    <th class="p-4 text-left">Reason</th>
                    <th class="p-4 text-left">Date From</th>
                    <th class="p-4 text-left">Date To</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($leaves as $leave)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="p-4">{{ $leave->personnel->fullname ?? 'N/A' }}</td>

                        <td class="p-4">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">
                                {{ $leave->leave_type }}
                            </span>
                        </td>

                        <td class="p-4">{{ $leave->reason }}</td>

                        <td class="p-4">{{ $leave->start_date->format('M d, Y') }}</td>
                        <td class="p-4">{{ $leave->end_date->format('M d, Y') }}</td>

                        <td class="p-4">
                            @if($leave->status == 'Pending')
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">⏳ Pending</span>
                            @elseif($leave->status == 'Approved')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">✅ Approved</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">❌ Rejected</span>
                                @if($leave->rejection_reason)
                                    <div class="text-xs text-gray-500 mt-1 italic">"{{ $leave->rejection_reason }}"</div>
                                @endif
                            @endif
                        </td>

                        <td class="p-4">
                            @if($leave->status == 'Pending')

                                <form id="approve-form-{{ $leave->id }}" method="POST" action="{{ route('utility-leave.approve', $leave->id) }}" class="inline">
                                    @csrf
                                    <button type="button" onclick="confirmApprove({{ $leave->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                                        ✅ Approve
                                    </button>
                                </form>

                                <form id="reject-form-{{ $leave->id }}" method="POST" action="{{ route('utility-leave.reject', $leave->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="rejection_reason" id="reject-reason-{{ $leave->id }}">
                                    <button type="button" onclick="confirmReject({{ $leave->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow">
                                        ❌ Reject
                                    </button>
                                </form>

                            @endif
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center p-4 text-gray-500">
                            No utility leave requests found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmApprove(id) {
    Swal.fire({
        title: 'Approve Leave?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('approve-form-' + id).submit();
        }
    });
}

function confirmReject(id) {
    Swal.fire({
        title: 'Reject Leave?',
        text: 'Optional: let them know why (e.g. "already covered that day by someone else").',
        input: 'textarea',
        inputPlaceholder: 'Reason (optional) — leave blank to reject without a note',
        inputAttributes: { 'aria-label': 'Rejection reason' },
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Reject'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reject-reason-' + id).value = result.value || '';
            document.getElementById('reject-form-' + id).submit();
        }
    });
}
</script>

@endsection

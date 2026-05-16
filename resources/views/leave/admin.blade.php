@extends('layouts.app')

@section('content')

<div class="p-6">

    <h2 class="text-xl font-bold mb-4">All Leave Requests (Supervisor)</h2>

    <!-- FILTER -->
    <div class="mb-4 space-x-2">

        <a href="/leave/admin"
        class="px-3 py-1 rounded {{ !$status ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            All
        </a>

        <a href="/leave/admin?status=Pending"
        class="px-3 py-1 rounded {{ $status == 'Pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200' }}">
            Pending
        </a>

        <a href="/leave/admin?status=Approved"
        class="px-3 py-1 rounded {{ $status == 'Approved' ? 'bg-green-600 text-white' : 'bg-gray-200' }}">
            Approved
        </a>

        <a href="/leave/admin?status=Rejected"
        class="px-3 py-1 rounded {{ $status == 'Rejected' ? 'bg-red-600 text-white' : 'bg-gray-200' }}">
            Rejected
        </a>

    </div>

    <!-- SEARCH -->
    <form method="GET" action="/leave/admin" class="mb-4 flex gap-2">
        <input type="text" name="search"
            value="{{ $search ?? '' }}"
            placeholder="Search employee name..."
            class="border px-3 py-1 rounded w-64">

        <input type="hidden" name="status" value="{{ $status }}">

        <button class="bg-blue-600 text-white px-3 py-1 rounded">
            Search
        </button>
    </form>

    <!-- TABLE -->
    <div class="bg-white rounded shadow p-4">

    <table class="w-full border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">Name</th>
                <th class="p-2 border">Type</th>
                <th class="p-2 border">Approved At</th>
                <th class="p-2 border">Date From</th>
                <th class="p-2 border">Date To</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($leaves as $leave)
            <tr>

                <td class="p-2 border">{{ $leave->user->name ?? 'N/A' }}</td>

                <td class="p-2 border">{{ $leave->type }}</td>

                <td class="p-2 border">
                    @if($leave->approved_at)
                        {{ \Carbon\Carbon::parse($leave->approved_at)->format('M d, Y h:i A') }}
                        <br>
                        <small class="text-gray-500">
                            by {{ $leave->approver->name ?? 'N/A' }}
                        </small>
                    @else
                        -
                    @endif
                </td>

                <td class="p-2 border">{{ $leave->date_from }}</td>
                <td class="p-2 border">{{ $leave->date_to }}</td>

                <td class="p-2 border">
                    <span class="px-2 py-1 rounded
                        {{ $leave->status == 'Pending' ? 'bg-yellow-200' : ($leave->status == 'Approved' ? 'bg-green-200' : 'bg-red-200') }}">
                        {{ $leave->status }}
                    </span>
                </td>

                <td class="p-2 border">
                    @if($leave->status == 'Pending')

                        <!-- APPROVE -->
                        <form id="approve-form-{{ $leave->id }}" method="POST" action="/leave/approve/{{ $leave->id }}" class="inline">
                            @csrf
                            <button type="button"
                                onclick="confirmApprove({{ $leave->id }})"
                                class="bg-green-600 text-white px-3 py-1 rounded">
                                Approve
                            </button>
                        </form>

                        <!-- REJECT -->
                        <form id="reject-form-{{ $leave->id }}" method="POST" action="/leave/reject/{{ $leave->id }}" class="inline">
                            @csrf
                            <button type="button"
                                onclick="confirmReject({{ $leave->id }})"
                                class="bg-red-600 text-white px-3 py-1 rounded">
                                Reject
                            </button>
                        </form>

                    @endif
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center p-4 text-gray-500">
                    No leave requests found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    </div>

</div>

<!-- SWEETALERT -->
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
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, reject it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reject-form-' + id).submit();
        }
    });
}
</script>

@endsection
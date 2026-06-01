@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto mt-8">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-3xl font-bold text-white flex items-center gap-2">
            📄 Leave Management
        </h2>

    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h3 class="text-gray-500 text-sm">
                Pending
            </h3>

            <p class="text-3xl font-bold text-yellow-500 mt-2">
                {{ $leaves->where('status','Pending')->count() }}
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h3 class="text-gray-500 text-sm">
                ✅ Approved
            </h3>

            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $leaves->where('status','Approved')->count() }}
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h3 class="text-gray-500 text-sm">
                Rejected
            </h3>

            <p class="text-3xl font-bold text-red-600 mt-2">
                {{ $leaves->where('status','Rejected')->count() }}
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h3 class="text-gray-500 text-sm">
                Total Requests
            </h3>

            <p class="text-3xl font-bold text-blue-600 mt-2">
                {{ $leaves->count() }}
            </p>
        </div>

    </div>

    <!-- FILTER -->
    <div class="bg-white rounded-2xl shadow-xl p-4 mb-6">

    <div class="flex flex-wrap gap-2 mb-4">

        <a href="/leave/admin"
        class="px-4 py-2 rounded-xl font-semibold
        {{ !$status ? 'bg-blue-600 text-white' : 'bg-gray-100' }}">
            All
        </a>

        <a href="/leave/admin?status=Pending"
        class="px-4 py-2 rounded-xl font-semibold
        {{ $status == 'Pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100' }}">
            Pending
        </a>

        <a href="/leave/admin?status=Approved"
        class="px-4 py-2 rounded-xl font-semibold
        {{ $status == 'Approved' ? 'bg-green-600 text-white' : 'bg-gray-100' }}">
            Approved
        </a>

        <a href="/leave/admin?status=Rejected"
        class="px-4 py-2 rounded-xl font-semibold
        {{ $status == 'Rejected' ? 'bg-red-600 text-white' : 'bg-gray-100' }}">
            Rejected
        </a>

    </div>

    <form method="GET" action="/leave/admin">

        <div class="flex gap-3">

            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Search employee..."
                class="w-full border rounded-xl p-3">

            <input type="hidden"
                name="status"
                value="{{ $status }}">

            <button
                class="bg-blue-600 text-white px-6 rounded-xl hover:bg-blue-700">

                Search

            </button>

        </div>

    </form>

</div>

    <!-- TABLE -->
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">

    <table class="min-w-full">
        <thead class="bg-gradient-to-r from-green-500 to-blue-600 text-white">
            <tr>
                <th class="p-4 text-left">Name</th>
                <th class="p-4 text-left">Type</th>
                <th class="p-4 text-left">Reason</th>
                <th class="p-4 text-left">Approved At</th>
                <th class="p-4 text-left">Date From</th>
                <th class="p-4 text-left">Date To</th>
                <th class="p-4 text-left">Status</th>
                <th class="p-4 text-left">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($leaves as $leave)
            <tr class="border-b hover:bg-gray-50 transition">

                <td class="p-4">
                    {{ $leave->personnel->fullname ?? $leave->personnel->user->name ?? 'N/A' }}
                </td>

                <td class="p-4">
                    {{ $leave->type }}
                </td>

                <td class="p-4">
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

                <td class="p-4">{{ $leave->date_from }}</td>
                <td class="p-4">{{ $leave->date_to }}</td>

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

                    @endif
                </td>

                <td class="p-4">
                    @if($leave->status == 'Pending')

                        <!-- APPROVE -->
                        <form id="approve-form-{{ $leave->id }}" method="POST" action="/leave/approve/{{ $leave->id }}" class="inline">
                            @csrf
                            <button type="button"
                                onclick="confirmApprove({{ $leave->id }})"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                                ✅ Approve
                            </button>
                        </form>

                        <!-- REJECT -->
                        <form id="reject-form-{{ $leave->id }}" method="POST" action="/leave/reject/{{ $leave->id }}" class="inline">
                            @csrf
                            <button type="button"
                                onclick="confirmReject({{ $leave->id }})"
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
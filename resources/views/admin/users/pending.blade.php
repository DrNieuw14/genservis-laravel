@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto mt-8">

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">

    <h2 class="text-3xl font-bold text-white flex items-center gap-2">
        👥 User Approvals
    </h2>

</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl mb-6">
        {{ session('success') }}
    </div>
@endif

<!-- STATS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    <!-- PENDING -->
    <div class="bg-white rounded-2xl shadow-xl p-6">

        <h3 class="text-gray-500 text-sm">
            Pending Users
        </h3>

        <p class="text-3xl font-bold text-yellow-500 mt-2">
            {{ $pendingCount }}
        </p>

    </div>

    <!-- APPROVED -->
    <div class="bg-white rounded-2xl shadow-xl p-6">

        <h3 class="text-gray-500 text-sm">
            Approved Users
        </h3>

        <p class="text-3xl font-bold text-green-600 mt-2">
            {{ $approvedCount }}
        </p>

    </div>

    <!-- REJECTED -->
    <div class="bg-white rounded-2xl shadow-xl p-6">

        <h3 class="text-gray-500 text-sm">
            Rejected Users
        </h3>

        <p class="text-3xl font-bold text-red-600 mt-2">
            {{ $rejectedCount }}
        </p>

    </div>

</div>

<!-- TABLE -->
<div class="bg-white shadow-2xl rounded-2xl overflow-hidden">

    <table class="min-w-full">

        <thead class="bg-gradient-to-r from-green-500 to-blue-600 text-white">

            <tr>

                <th class="p-4 text-left">
                    Name
                </th>

                <th class="p-4 text-left">
                    Username
                </th>

                <th class="p-4 text-center">
                    Actions
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($users as $user)

            <tr class="border-b hover:bg-gray-50 transition">

                <td class="p-4 font-medium">
                    {{ $user->fullname ?? $user->name }}
                </td>

                <td class="p-4">
                    {{ $user->username }}
                </td>

                <td class="p-4">

                    <div class="flex justify-center gap-2">

                        <form id="approve-user-{{ $user->id }}"
                            method="POST"
                            action="{{ route('admin.users.approve', $user->id) }}">
                            @csrf

                            <button type="button"
                                onclick="confirmUserApprove({{ $user->id }})"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">

                                ✅ Approve

                            </button>

                        </form>

                        <form id="reject-user-{{ $user->id }}"
                            method="POST"
                            action="{{ route('admin.users.reject', $user->id) }}">
                            @csrf

                            <button type="button"
                                onclick="confirmUserReject({{ $user->id }})"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow">

                                ❌ Reject

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="3"
                    class="text-center text-gray-500 py-10">

                    No pending user registrations.

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>
```

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmUserApprove(id) {
    Swal.fire({
        title: 'Approve User?',
        text: "This will allow the user to access the system.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        confirmButtonText: 'Approve'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('approve-user-' + id).submit();
        }
    });
}

function confirmUserReject(id) {
    Swal.fire({
        title: 'Reject User?',
        text: "This user will not be allowed to login.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Reject'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reject-user-' + id).submit();
        }
    });
}
</script>

@endsection

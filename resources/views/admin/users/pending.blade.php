@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            👥 User Approvals
        </h2>

    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl mb-6 text-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <!-- PENDING -->
        <div class="bg-yellow-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Pending Users</div>
            <div class="text-4xl font-bold">{{ $pendingCount }}</div>
        </div>

        <!-- APPROVED -->
        <div class="bg-green-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Approved Users</div>
            <div class="text-4xl font-bold">{{ $approvedCount }}</div>
        </div>

        <!-- REJECTED -->
        <div class="bg-red-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Rejected Users</div>
            <div class="text-4xl font-bold">{{ $rejectedCount }}</div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="border rounded-lg overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-lg">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-4 text-left text-gray-800">
                            Name
                        </th>

                        <th class="p-4 text-left text-gray-800">
                            Username
                        </th>

                        <th class="p-4 text-center text-gray-800">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse($users as $user)

                    <tr class="hover:bg-gray-50 transition">

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

                                    <a href="{{ route('admin.users.onboarding', $user) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow inline-flex items-center">

                                        📝 Complete Onboarding

                                    </a>

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
                            class="text-center text-gray-500 py-10 text-lg">

                            No pending user registrations.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
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

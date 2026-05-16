@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Pending User Approvals</h2>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white p-4 rounded shadow">

<table class="w-full border">
    <thead>
        <tr class="bg-gray-200">
            <th class="p-2">Name</th>
            <th class="p-2">Username</th>
            <th class="p-2">Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach($users as $user)
        <tr class="border-t">

            <!-- NAME -->
            <td class="p-2">
                {{ $user->fullname ?? $user->name }}
            </td>

            <!-- USERNAME -->
            <td class="p-2">
                {{ $user->username }}
            </td>

            <!-- ACTION (IMPORTANT) -->
            <td class="p-2 text-center">
                <div class="flex justify-center gap-2">

                    <!-- APPROVE -->
                    <form id="approve-user-{{ $user->id }}"
                        method="POST"
                        action="{{ route('admin.users.approve', $user->id) }}">
                        @csrf
                        <button type="button"
                            onclick="confirmUserApprove({{ $user->id }})"
                            class="bg-green-600 text-white px-3 py-1 rounded">
                            Approve
                        </button>
                    </form>

                    <!-- REJECT -->
                    <form id="reject-user-{{ $user->id }}"
                        method="POST"
                        action="{{ route('admin.users.reject', $user->id) }}">
                        @csrf
                        <button type="button"
                            onclick="confirmUserReject({{ $user->id }})"
                            class="bg-red-600 text-white px-3 py-1 rounded">
                            Reject
                        </button>
                    </form>

                </div>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmUserApprove(id) {
    Swal.fire({
        title: 'Approve User?',
        text: "This will allow the user to access the system.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve!'
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
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, reject!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reject-user-' + id).submit();
        }
    });
}
</script>

@endsection
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Supervisor Dashboard</h2>
    </x-slot>
    <div class="py-8 px-4 max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Stats Row --}}
        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
                <p class="text-4xl font-bold text-yellow-600">{{ $pendingCount }}</p>
                <p class="text-sm text-yellow-700 mt-1 font-medium">Pending Approval</p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                <p class="text-4xl font-bold text-green-600">{{ $approvedCount }}</p>
                <p class="text-sm text-green-700 mt-1 font-medium">Approved Users</p>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                <p class="text-4xl font-bold text-red-600">{{ $rejectedCount }}</p>
                <p class="text-sm text-red-700 mt-1 font-medium">Rejected Users</p>
            </div>
        </div>

       {{-- Pending Users --}}
            <div class="bg-white rounded-xl shadow border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <h3 class="font-semibold text-gray-700">Pending Registrations</h3>
                    <a href="{{ route('admin.users.pending') }}"
                    class="text-sm text-blue-600 hover:underline">View all →</a>
                </div>

                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3 text-left">Username</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Registered</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendingUsers as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->username ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $user->created_at?->diffForHumans() ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('admin.users.approve', $user->id) }}">
                                        @csrf
                                        <button type="button"
                                            onclick="openModal(this.closest('form'), 'Approve this user?')"
                                            class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs hover:bg-green-700">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.reject', $user->id) }}">
                                        @csrf
                                        <button type="button"
                                            onclick="openModal(this.closest('form'), 'Reject this user?')"
                                            class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                No pending registrations.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            {{-- Pending Leave Requests --}}
            <div id="leave-section" class="bg-white rounded-xl shadow border border-gray-100">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-700">Pending Leave Requests</h3>
                </div>

                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3 text-left">Reason</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @php
                            $leaves = \App\Models\LeaveRequest::where('status','Pending')
                                ->with('user')
                                ->latest()
                                ->get();
                        @endphp

                        @forelse($leaves as $leave)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $leave->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $leave->reason }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $leave->start_date }} - {{ $leave->end_date }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">
                                    {{ $leave->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ url('/leave/approve/'.$leave->id) }}">
                                        @csrf
                                        <button type="button"
                                            onclick="openModal(this.closest('form'), 'Approve this user?')"
                                            class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs hover:bg-green-700">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ url('/leave/reject/'.$leave->id) }}">
                                        @csrf
                                        <button type="button"
                                            onclick="openModal(this.closest('form'), 'Reject this user?')"
                                            class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                No pending leave requests.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </table>
        </div>
    </div>
</x-app-layout>
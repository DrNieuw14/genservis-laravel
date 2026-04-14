<x-app-layout>
    <div class="p-6">

        <h2 class="text-xl font-bold mb-4">All Leave Requests (Supervisor)</h2>
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

        <form method="GET" action="/leave/admin" class="mb-4 flex gap-2">

            <!-- 🔍 Search Input -->
            <input type="text" name="search"
                value="{{ $search ?? '' }}"
                placeholder="Search employee name..."
                class="border px-3 py-1 rounded w-64">

            <!-- Keep status when searching -->
            <input type="hidden" name="status" value="{{ $status }}">

            <!-- Button -->
            <button class="bg-blue-600 text-white px-3 py-1 rounded">
                Search
            </button>

        </form>

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
                @foreach($leaves as $leave)
                
                <tr>

                    <!-- Name -->
                    <td class="p-2 border">
                        {{ $leave->user->name ?? 'N/A' }}
                    </td>

                    <!-- Type -->
                    <td class="p-2 border">{{ $leave->type }}</td>

                    <!-- Approved At + By -->
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

                    <!-- Date From -->
                    <td class="p-2 border">{{ $leave->date_from }}</td>

                    <!-- Date To -->
                    <td class="p-2 border">{{ $leave->date_to }}</td>

                    <!-- Status -->
                    <td class="p-2 border">
                        <span class="px-2 py-1 rounded
                            {{ $leave->status == 'Pending' ? 'bg-yellow-200' : ($leave->status == 'Approved' ? 'bg-green-200' : 'bg-red-200') }}">
                            {{ $leave->status }}
                        </span>
                    </td>

                    <!-- Action -->
                    <td class="p-2 border">
                        @if($leave->status == 'Pending')
                            <form method="POST" action="/leave/approve/{{ $leave->id }}" style="display:inline;">
                                @csrf
                                <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded font-bold shadow">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="/leave/reject/{{ $leave->id }}" style="display:inline;">
                                @csrf
                                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded font-bold shadow">
                                    Reject
                                </button>
                            </form>
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>
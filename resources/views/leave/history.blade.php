<x-app-layout>

<div class="p-6">

    <!-- TITLE -->
    <h2 class="text-xl font-bold mb-4">My Leave Requests</h2>

    <!-- ✅ SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 mb-4 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABLE -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Date From</th>
                    <th class="p-3 text-left">Date To</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Submitted</th>
                </tr>
            </thead>

            <tbody>
                @forelse($leaves as $leave)
                    <tr class="border-t">

                        <td class="p-3">{{ $leave->type }}</td>

                        <td class="p-3">{{ $leave->date_from }}</td>

                        <td class="p-3">{{ $leave->date_to }}</td>

                        <td class="p-3">
                            @if($leave->status == 'Pending')
                                <span class="bg-yellow-400 text-white px-2 py-1 rounded text-xs">Pending</span>
                            @elseif($leave->status == 'Approved')
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">Approved</span>
                            @else
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-xs">Rejected</span>
                            @endif
                        </td>

                        <td class="p-3">{{ $leave->created_at->format('Y-m-d') }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">
                            No leave requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

</x-app-layout>
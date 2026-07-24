@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🔄 Sports Equipment Borrow Requests
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Requests submitted by users through Material Request's "Borrow Sports Equipment" option.
            </p>
        </div>

        @if(auth()->user()->hasPermission('manage-sports-equipment-inventory'))
            <x-back-button :href="route('sports-equipment.index')" />
        @endif

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Ref #</th>
                    <th class="p-3 text-left">Equipment</th>
                    <th class="p-3 text-left">Borrower</th>
                    <th class="p-3 text-left">Department</th>
                    <th class="p-3 text-center">Qty</th>
                    <th class="p-3 text-center">Expected Return</th>
                    <th class="p-3 text-center">Status</th>
                    @if($canAct)
                        <th class="p-3 text-center">Action</th>
                    @endif
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($borrows as $borrow)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-mono text-sm">{{ $borrow->borrow_number }}</td>
                        <td class="p-3">{{ $borrow->equipment->name }}</td>
                        <td class="p-3">{{ $borrow->user->fullname ?? $borrow->user->username }}</td>
                        <td class="p-3">{{ $borrow->department->department_name ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $borrow->quantity }}</td>
                        <td class="p-3 text-center">{{ $borrow->expected_return_date->format('M d, Y') }}</td>
                        <td class="p-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $borrow->statusBadgeClass() }}">
                                {{ ucfirst($borrow->displayStatus()) }}
                            </span>
                        </td>

                        @if($canAct)
                            <td class="p-3 text-center whitespace-nowrap">

                                @if($borrow->status === 'pending')

                                    <form action="{{ route('sports-equipment.borrows.approve', $borrow->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:underline text-sm mr-3">
                                            ✔ Approve
                                        </button>
                                    </form>

                                    <button type="button"
                                        onclick="openRejectModal({{ $borrow->id }})"
                                        class="text-red-600 hover:underline text-sm">
                                        ✖ Reject
                                    </button>

                                @elseif($borrow->status === 'approved')

                                    <button type="button"
                                        onclick="openReturnModal({{ $borrow->id }})"
                                        class="text-blue-600 hover:underline text-sm">
                                        📥 Log Return
                                    </button>

                                @else

                                    <span class="text-gray-400 text-sm">—</span>

                                @endif

                            </td>
                        @endif
                    </tr>

                @empty

                    <tr>
                        <td colspan="{{ $canAct ? 8 : 7 }}" class="p-6 text-center text-gray-500">
                            No borrow requests yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@if($canAct)

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">✖ Reject Borrow Request</h2>

            <form id="rejectForm" method="POST">
                @csrf

                <label class="block mb-2 font-semibold text-sm">Reason (optional)</label>
                <textarea name="rejection_reason" rows="3" class="w-full border rounded-lg p-3"></textarea>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeRejectModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded">
                        Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Return Modal -->
    <div id="returnModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">📥 Log Equipment Return</h2>

            <form id="returnForm" method="POST">
                @csrf

                <label class="block mb-2 font-semibold text-sm">Condition on Return</label>
                <select name="condition_on_return" class="w-full border rounded-lg p-3 mb-4" required>
                    <option value="">-- Select Condition --</option>
                    @foreach(\App\Models\SportsEquipmentBorrow::CONDITIONS as $condition)
                        <option value="{{ $condition }}">{{ $condition }}</option>
                    @endforeach
                </select>

                <label class="block mb-2 font-semibold text-sm">Remarks (optional)</label>
                <textarea name="remarks" rows="3" class="w-full border rounded-lg p-3"></textarea>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeReturnModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                        Confirm Return
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>

        function openRejectModal(id)
        {
            document.getElementById('rejectForm').action = `/sports-equipment-borrows/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal()
        {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }

        function openReturnModal(id)
        {
            document.getElementById('returnForm').action = `/sports-equipment-borrows/${id}/return`;
            document.getElementById('returnModal').classList.remove('hidden');
            document.getElementById('returnModal').classList.add('flex');
        }

        function closeReturnModal()
        {
            document.getElementById('returnModal').classList.add('hidden');
            document.getElementById('returnModal').classList.remove('flex');
        }

    </script>

@endif

@endsection

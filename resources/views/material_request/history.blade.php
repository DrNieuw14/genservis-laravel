@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- PAGE HEADER -->
    <div class="mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            📜 My Material Requests
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Track your submitted material requests and approval status.
        </p>
    </div>

    <!-- REQUEST SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-yellow-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Pending Requests</div>
            <div class="text-4xl font-bold">{{ $pendingCount }}</div>
        </div>

        <div class="bg-green-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Approved Requests</div>
            <div class="text-4xl font-bold">{{ $approvedCount }}</div>
        </div>

        <div class="bg-blue-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Released Requests</div>
            <div class="text-4xl font-bold">{{ $releasedCount }}</div>
        </div>

        <div class="bg-red-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Rejected Requests</div>
            <div class="text-4xl font-bold">{{ $rejectedCount }}</div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="border rounded-lg overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-lg" id="historyTable">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="p-4 text-left text-gray-800">Request No.</th>
                        <th class="p-4 text-left text-gray-800">Department</th>
                        <th class="p-4 text-left text-gray-800">Room</th>
                        <th class="p-4 text-left text-gray-800">Material</th>
                        <th class="p-4 text-left text-gray-800">Qty</th>
                        <th class="p-4 text-left text-gray-800">Purpose</th>
                        <th class="p-4 text-left text-gray-800">Date Requested</th>
                        <th class="p-4 text-left text-gray-800">Status</th>
                        <th class="p-4 text-left text-gray-800">Actions</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse($requests as $req)

                        <tr class="hover:bg-blue-50 transition">

                            <!-- REQUEST NUMBER -->
                            <td class="p-4">

                                <button
                                    onclick="openRequestModal({{ $req->id }})"
                                    class="font-semibold text-blue-600 hover:text-blue-800 hover:underline">

                                    MR-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}

                                </button>

                            </td>

                            <!-- DEPARTMENT -->
                            <td class="p-4 text-gray-700 font-medium">
                                {{ $req->department->department_name ?? 'N/A' }}
                            </td>

                            <!-- ROOM -->
                            <td class="p-4 text-gray-700">
                                {{ $req->room ?? '—' }}
                            </td>

                            <!-- MATERIAL -->
                            <td class="p-4">
                                @foreach($req->items as $item)
                                    <div>
                                        {{ $item->material->name }}
                                    </div>
                                @endforeach
                            </td>

                            <!-- QUANTITY -->
                            <td class="p-4">
                                @foreach($req->items as $item)
                                    <div>
                                        {{ $item->quantity }}
                                    </div>
                                @endforeach
                            </td>

                            <!-- PURPOSE -->
                            <td class="p-4 text-gray-600">
                                {{ $req->purpose }}
                            </td>

                            <!-- DATE -->
                            <td class="p-4 text-gray-500 text-base">
                                {{ $req->created_at->format('M d, Y') }}
                                <br>
                                <span class="text-gray-400">
                                    {{ $req->created_at->format('h:i A') }}
                                </span>
                            </td>

                            <!-- STATUS -->
                            <td class="p-4">

                                @if($req->status == 'pending')

                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-base font-semibold">
                                        ⏳ Pending
                                    </span>

                                @elseif($req->status == 'approved')

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-base font-semibold">
                                        ✅ Approved
                                    </span>

                                @elseif($req->status == 'released')

                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-base font-semibold">
                                        📦 Released
                                    </span>

                                @else

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-base font-semibold">
                                        ❌ Rejected
                                    </span>

                                @endif

                            </td>

                            <!-- ACTIONS -->
                            <td class="p-4">

                                <a href="{{ route('material-request.slip', $req->id) }}"
                                target="_blank"
                                class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-base font-semibold">

                                    🖨 Print Slip

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="p-6 text-center text-gray-500 text-lg">
                                No material requests found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- HIDDEN REQUEST DETAIL DATA (kept outside the table — a <div> is not valid inside a <tr>) -->
@foreach($requests as $req)

    <div id="request-data-{{ $req->id }}" class="hidden">

        <div class="space-y-3 text-lg">

            <div>
                <strong>Request Number:</strong>
                MR-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}
            </div>

            <div>
                <strong>Department:</strong>
                {{ $req->department->department_name ?? 'N/A' }}
            </div>

            @if($req->room)
            <div>
                <strong>Room / Location:</strong>
                {{ $req->room }}
            </div>
            @endif

            <div>
                <strong>Purpose:</strong>
                {{ $req->purpose }}
            </div>

            <div>
                <strong>Status:</strong>
                {{ ucfirst($req->status) }}
            </div>

            <div>
                <strong>Date Requested:</strong>
                {{ $req->created_at->format('M d, Y h:i A') }}
            </div>

            <hr class="my-2">

            <div>

                <strong>Requested Materials</strong>

                <div class="border rounded-lg mt-2 overflow-hidden">

                    <table class="w-full text-base">

                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left p-2">Material</th>
                                <th class="text-right p-2">Qty</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            @foreach($req->items as $item)

                                <tr>
                                    <td class="p-2">{{ $item->material->name }}</td>
                                    <td class="p-2 text-right">{{ $item->quantity }}</td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endforeach

<!-- REQUEST DETAILS MODAL -->

<div
    id="requestModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6 lg:p-8">

        <div class="flex justify-between items-center mb-4">

            <h3 class="text-2xl font-bold text-gray-800">
                Request Details
            </h3>

            <button
                onclick="closeRequestModal()"
                class="text-gray-500 hover:text-red-500 text-3xl leading-none">

                ✖

            </button>

        </div>

        <div id="requestDetails">

        </div>

    </div>

</div>

<script>

    function openRequestModal(id)
    {
        const requestData = document.getElementById(
            'request-data-' + id
        );

        document.getElementById(
            'requestDetails'
        ).innerHTML = requestData.innerHTML;

        document.getElementById(
            'requestModal'
        ).classList.remove('hidden');

        document.getElementById(
            'requestModal'
        ).classList.add('flex');
    }

    function closeRequestModal()
    {
        document.getElementById(
            'requestModal'
        ).classList.add('hidden');

        document.getElementById(
            'requestModal'
        ).classList.remove('flex');
    }

</script>

@endsection

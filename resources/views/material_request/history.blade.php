@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <!-- PAGE HEADER -->
    <div class="mb-6">
        <h2 class="text-4xl font-bold text-white flex items-center gap-3">
            📜 My Material Requests
        </h2>

        <p class="text-white/80 mt-2">
            Track your submitted material requests and approval status.
        </p>
    </div>

    <!-- REQUEST SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-yellow-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-sm">Pending Requests</div>
            <div class="text-3xl font-bold">{{ $pendingCount }}</div>
        </div>

        <div class="bg-green-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-sm">Approved Requests</div>
            <div class="text-3xl font-bold">{{ $approvedCount }}</div>
        </div>

        <div class="bg-blue-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-sm">Released Requests</div>
            <div class="text-3xl font-bold">{{ $releasedCount }}</div>
        </div>

        <div class="bg-red-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-sm">Rejected Requests</div>
            <div class="text-3xl font-bold">{{ $rejectedCount }}</div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gradient-to-r from-green-500 to-blue-500 text-white">

                <tr>
                    <th class="p-4 text-left">Request No.</th>
                    <th class="p-4 text-left">Department</th>
                    <th class="p-4 text-left">Material</th>
                    <th class="p-4 text-left">Qty</th>
                    <th class="p-4 text-left">Purpose</th>
                    <th class="p-4 text-left">Date Requested</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Actions</th>
                </tr>

            </thead>

            <tbody>

                @forelse($requests as $req)

                    <tr class="border-b hover:bg-gray-50 transition">

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
                        <td class="p-4 text-gray-500">
                            {{ $req->created_at->format('M d, Y h:i A') }}
                        </td>

                        <!-- STATUS -->
                        <td class="p-4">

                            @if($req->status == 'pending')

                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    ⏳ Pending
                                </span>

                            @elseif($req->status == 'approved')

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    ✅ Approved
                                </span>

                            @elseif($req->status == 'released')

                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    📦 Released
                                </span>

                            @else

                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    ❌ Rejected
                                </span>

                            @endif

                        </td>

                        <!-- ACTIONS -->
                        <td class="p-4">

                            <a href="{{ route('material-request.slip', $req->id) }}"
                            target="_blank"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm">

                                🖨 Print Slip

                            </a>

                        </td>
                        
                        <div
                            id="request-data-{{ $req->id }}"
                            class="hidden">

                            <div class="space-y-3">

                                <div>
                                    <strong>Request Number:</strong>
                                    MR-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}
                                </div>

                                <div>
                                    <strong>Department:</strong>
                                    {{ $req->department->department_name ?? 'N/A' }}
                                </div>

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

                                <hr>

                                <div>

                                    <strong>Requested Materials:</strong>

                                    <ul class="list-disc ml-6 mt-2">

                                        @foreach($req->items as $item)

                                            <li>

                                                {{ $item->material->name }}
                                                (Qty: {{ $item->quantity }})

                                            </li>

                                        @endforeach

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            No material requests found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- REQUEST DETAILS MODAL -->

<div
    id="requestModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">

        <div class="flex justify-between items-center mb-4">

            <h3 class="text-xl font-bold text-gray-800">
                Request Details
            </h3>

            <button
                onclick="closeRequestModal()"
                class="text-gray-500 hover:text-red-500 text-xl">

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
    }

    function closeRequestModal()
    {
        document.getElementById(
            'requestModal'
        ).classList.add('hidden');
    }

</script>

@endsection
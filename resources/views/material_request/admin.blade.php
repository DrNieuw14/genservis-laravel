@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto mt-8">

    <!-- HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl font-bold text-white flex items-center gap-2">
            📦 Material Requests
        </h2>

        <p class="text-white/80 mt-2">
            Manage and monitor personnel material requests.
        </p>

    </div>

    <!-- SUCCESS -->
    @if(session('success'))

        <div class="bg-green-500 text-white p-4 rounded-xl mb-4 shadow">
            {{ session('success') }}
        </div>

    @endif

    <!-- ERROR -->
    @if(session('error'))

        <div class="bg-red-500 text-white p-4 rounded-xl mb-4 shadow">
            {{ session('error') }}
        </div>

    @endif

    <!-- REQUEST SUMMARY -->

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-yellow-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-sm">
                Pending Requests
            </div>

            <div class="text-3xl font-bold">
                {{ $pendingCount }}
            </div>
        </div>

        <div class="bg-green-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-sm">
                Approved Requests
            </div>

            <div class="text-3xl font-bold">
                {{ $approvedCount }}
            </div>
        </div>

        <div class="bg-blue-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-sm">
                Released Requests
            </div>

            <div class="text-3xl font-bold">
                {{ $releasedCount }}
            </div>
        </div>

        <div class="bg-red-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-sm">
                Rejected Requests
            </div>

            <div class="text-3xl font-bold">
                {{ $rejectedCount }}
            </div>
        </div>

    </div>

    <!-- TABLE CARD -->
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">

        <table class="w-full">

            <!-- HEADER -->
            <thead class="bg-gradient-to-r from-green-500 to-blue-600 text-white">

                <tr>
                    <th class="p-4 text-left">Request No.</th>

                    <th class="p-4 text-left">Requester</th>

                    <th class="p-4 text-left">Department</th>

                    <th class="p-4 text-left">Material</th>

                    <th class="p-4 text-left">Qty</th>

                    <th class="p-4 text-left">Purpose</th>

                    <th class="p-4 text-left">Requested At</th>

                    <th class="p-4 text-left">Status</th>

                    <th class="p-4 text-left">Actions</th>

                </tr>

            </thead>

            <!-- BODY -->
            <tbody>

    @forelse($requests as $request)

        <tr class="border-b hover:bg-gray-50 transition">

            <!-- REQUEST NUMBER -->
            <td class="p-4">
                <button
                    onclick="openRequestModal({{ $request->id }})"
                    class="font-bold text-blue-600 hover:text-blue-800 hover:underline">

                    {{ $request->request_number }}

                </button>
            </td>

            <!-- USER -->
            <td class="p-4 font-medium">
                {{ $request->user->fullname ?? $request->user->username }}
            </td>

            <!-- DEPARTMENT -->
            <td class="px-4 py-4">
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                    🏢 {{ $request->department->department_code ?? 'N/A' }}
                </span>
            </td>

            <!-- MATERIAL -->
            <td class="p-4">
                @foreach($request->items as $item)
                    <div>{{ $item->material->name ?? '-' }}</div>
                @endforeach
            </td>

            <!-- QUANTITY -->
            <td class="p-4">
                @foreach($request->items as $item)
                    <div>{{ $item->quantity }}</div>
                @endforeach
            </td>

            <!-- PURPOSE -->
            <td class="p-4 text-gray-700">
                {{ $request->purpose }}
            </td>

            <!-- DATE -->
            <td class="p-4 text-sm text-gray-600">
                {{ $request->created_at->format('M d, Y') }}
                <br>
                <span class="text-xs text-gray-400">
                    {{ $request->created_at->format('h:i A') }}
                </span>
            </td>

            <!-- STATUS -->
            <td class="p-4">

                @if($request->status == 'pending')

                    <span class="bg-yellow-400 text-black px-3 py-1 rounded-full text-sm">
                        ⏳ Pending
                    </span>

                @elseif($request->status == 'approved')

                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm">
                        ✅ Approved
                    </span>

                @elseif($request->status == 'released')

                    <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm">
                        📦 Released
                    </span>

                @elseif($request->status == 'rejected')

                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm">
                        ❌ Rejected
                    </span>

                @endif

            </td>

            <!-- ACTIONS -->
            <td class="p-4">

                @if($request->status == 'pending')

                    <div class="flex gap-2">

                        <form
                            action="/supervisor/material-requests/{{ $request->id }}/approve"
                            method="POST">

                            @csrf

                            <button
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">

                                ✅ Approve

                            </button>

                        </form>

                        <form
                            action="/supervisor/material-requests/{{ $request->id }}/reject"
                            method="POST">

                            @csrf

                            <button
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">

                                ❌ Reject

                            </button>

                        </form>

                    </div>

                @elseif($request->status == 'approved')

                <form
                    action="/supervisor/material-requests/{{ $request->id }}/release"
                    method="POST">

                    @csrf

                    <button
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">

                        📦 Release

                    </button>

                </form>

                @else

                    <span class="text-gray-400 text-sm">
                        No actions available
                    </span>

                @endif

            </td>

        </tr>

        <!-- HIDDEN MODAL DATA -->
        <tr class="hidden">

            <td colspan="9">

                <div
                    id="request-{{ $request->id }}"
                    class="space-y-3">

                    <p>
                        <strong>Request Number:</strong>
                        {{ $request->request_number }}
                    </p>

                    <p>
                        <strong>Requester:</strong>
                        {{ $request->user->fullname ?? $request->user->username }}
                    </p>

                    <p>
                        <strong>Department:</strong>
                        {{ $request->department->department_name ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Purpose:</strong>
                        {{ $request->purpose }}
                    </p>

                    <p>
                        <strong>Status:</strong>
                        {{ ucfirst($request->status) }}
                    </p>

                    <p>
                        <strong>Date Requested:</strong>
                        {{ $request->created_at->format('M d, Y h:i A') }}
                    </p>

                    <hr>

                    <h4 class="font-bold mb-2">
                        Requested Materials
                    </h4>

                    <table class="w-full border">

                        <thead>

                            <tr class="bg-gray-100">

                                <th class="p-2 text-left">Material</th>
                                <th class="p-2 text-left">Requested</th>
                                <th class="p-2 text-left">Available</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($request->items as $item)

                            <tr>

                                <td class="p-2">
                                    {{ $item->material->name ?? 'Unknown Material' }}
                                </td>

                                <td class="p-2">
                                    {{ $item->quantity }}
                                </td>

                                <td class="p-2">
                                    {{ $item->material->quantity }}
                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>
         
                </div>

            </td>

        </tr>

                @empty

                    <tr>
                        <td colspan="9" class="p-6 text-center text-gray-500">
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
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 relative">

        <button
            onclick="closeRequestModal()"
            class="absolute top-3 right-4 text-3xl text-gray-500">

            ×

        </button>

        <h3 class="text-2xl font-bold mb-4">
            Request Details
        </h3>

        <div id="requestContent"></div>

    </div>

</div>

<script>

function openRequestModal(id)
{
    let content = document.getElementById('request-' + id).innerHTML;

    document.getElementById('requestContent').innerHTML = content;

    document.getElementById('requestModal')
        .classList.remove('hidden');

    document.getElementById('requestModal')
        .classList.add('flex');
}

function closeRequestModal()
{
    document.getElementById('requestModal')
        .classList.remove('flex');

    document.getElementById('requestModal')
        .classList.add('hidden');
}

</script>

@endsection
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

    <!-- TABLE CARD -->
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">

        <table class="w-full">

            <!-- HEADER -->
            <thead class="bg-gradient-to-r from-green-500 to-blue-600 text-white">

                <tr>

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

                        <!-- USER -->
                        <td class="p-4 font-medium">

                            {{ $request->user->fullname ?? $request->user->username }}

                        </td>

                        <td class="px-4 py-4">

                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">

                                🏢 {{ $request->department->department_code ?? 'N/A' }}

                            </span>

                        </td>

                        <!-- MATERIAL -->
                        <td class="p-4">

                            @foreach($request->items as $item)

                                <div>
                                    {{ $item->material->name ?? '-' }}
                                </div>

                            @endforeach

                        </td>

                        <!-- QUANTITY -->
                        <td class="p-4">

                            @foreach($request->items as $item)

                                <div>
                                    {{ $item->quantity }}
                                </div>

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

                                    <!-- APPROVE -->
                                    <form
                                        action="/supervisor/material-requests/{{ $request->id }}/approve"
                                        method="POST">

                                        @csrf

                                        <button
                                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">

                                            ✅ Approve

                                        </button>

                                    </form>

                                    <!-- REJECT -->
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

                            @else

                                <span class="text-gray-400 text-sm">
                                    No actions available
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8"
                            class="text-center text-gray-500 py-8">

                            No material requests found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
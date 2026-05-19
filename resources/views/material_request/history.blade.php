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

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gradient-to-r from-green-500 to-blue-500 text-white">

                <tr>
                    <th class="p-4 text-left">Request No.</th>
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
                        <td class="p-4 font-semibold text-gray-700">
                            MR-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}
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

@endsection
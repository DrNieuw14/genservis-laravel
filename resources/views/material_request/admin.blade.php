@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <h2 class="text-2xl font-bold mb-6 text-white">
        📦 Material Requests (Supervisor)
    </h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-3 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">

        <table class="w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">User</th>
                    <th class="p-3 border">Materials</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($requests as $req)
                <tr class="hover:bg-gray-50">

                    <!-- USER -->
                    <td class="p-3 border">
                        {{ $req->user->fullname ?? $req->user->username }}
                    </td>

                    <!-- MATERIALS -->
                    <td class="p-3 border">
                        @foreach($req->items as $item)
                            <div>
                                {{ $item->material->name }} 
                                (Qty: {{ $item->quantity }})
                            </div>
                        @endforeach
                    </td>

                    <!-- STATUS -->
                    <td class="p-3 border">
                        @if($req->status == 'pending')
                            <span class="bg-yellow-200 px-2 py-1 rounded">Pending</span>
                        @elseif($req->status == 'approved')
                            <span class="bg-green-200 px-2 py-1 rounded">Approved</span>
                        @else
                            <span class="bg-red-200 px-2 py-1 rounded">Rejected</span>
                        @endif
                    </td>

                    <!-- ACTION -->
                    <td class="p-3 border">

                        @if($req->status == 'pending')

                        <form method="POST" action="/supervisor/material-requests/{{ $req->id }}/approve" class="inline">
                            @csrf
                            <button class="bg-green-500 text-white px-3 py-1 rounded">
                                Approve
                            </button>
                        </form>

                        <form method="POST" action="/supervisor/material-requests/{{ $req->id }}/reject" class="inline">
                            @csrf
                            <button class="bg-red-500 text-white px-3 py-1 rounded">
                                Reject
                            </button>
                        </form>

                        @else
                            <span class="text-gray-400">No Action</span>
                        @endif

                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="4" class="text-center p-4 text-gray-500">
                        No requests found
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧾 Purchase Requests
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                The documents that actually consume a PPMP item's PRE ceiling.
            </p>

        </div>

        @if(auth()->user()->hasPermission('create-purchase-requests'))

        <a href="{{ route('procurement.purchase-requests.create') }}"
           class="bg-gradient-to-r from-green-500 to-blue-500
                  hover:scale-105 transition
                  text-white px-5 py-3 rounded-xl shadow-lg font-semibold whitespace-nowrap">

            ➕ New Purchase Request

        </a>

        @endif

    </div>

@if(session('success'))
<div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-lg">
{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6 text-lg">
{{ session('error') }}
</div>
@endif

<form method="GET" class="flex gap-3 mb-6">

    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search PR Number..." class="border rounded px-4 py-2 flex-1">

    <select name="status" class="border rounded px-4 py-2">
        <option value="">All Status</option>
        <option value="Draft" @selected(request('status')==='Draft')>Draft</option>
        <option value="Approved" @selected(request('status')==='Approved')>Approved</option>
        <option value="Completed" @selected(request('status')==='Completed')>Completed</option>
        <option value="Rejected" @selected(request('status')==='Rejected')>Rejected</option>
    </select>

    <button class="bg-blue-600 text-white rounded px-4">Search</button>

</form>

<table class="min-w-full">

<thead>
<tr class="border-b bg-gray-100">
    <th class="text-left px-4 py-3">PR No.</th>
    <th class="text-left px-4 py-3">Date</th>
    <th class="text-left px-4 py-3">Plan</th>
    <th class="text-left px-4 py-3">Status</th>
    <th class="text-right px-4 py-3">Total Cost</th>
    <th class="text-center px-4 py-3">Actions</th>
</tr>
</thead>

<tbody>

@forelse($purchaseRequests as $pr)

<tr class="border-b">
    <td class="px-4 py-3 font-semibold">{{ $pr->pr_number }}</td>
    <td class="px-4 py-3">{{ $pr->pr_date->format('M d, Y') }}</td>
    <td class="px-4 py-3">{{ $pr->plan->plan_number }}</td>
    <td class="px-4 py-3">
        <span class="px-3 py-1 rounded-full text-xs font-semibold
            {{ match($pr->status) {
                'Approved' => 'bg-blue-100 text-blue-700',
                'Completed' => 'bg-green-100 text-green-700',
                'Rejected' => 'bg-red-100 text-red-700',
                default => 'bg-yellow-100 text-yellow-700',
            } }}">
            {{ $pr->status }}
        </span>
    </td>
    <td class="px-4 py-3 text-right">₱ {{ number_format($pr->total_cost, 2) }}</td>
    <td class="px-4 py-3 text-center">
        <a href="{{ route('procurement.purchase-requests.show', $pr->id) }}" class="text-blue-600 hover:underline">View</a>
    </td>
</tr>

@empty

<tr><td colspan="6" class="text-center py-8 text-gray-500">No Purchase Requests Found</td></tr>

@endforelse

</tbody>

</table>

<div class="mt-6">{{ $purchaseRequests->links() }}</div>

</div>

@endsection

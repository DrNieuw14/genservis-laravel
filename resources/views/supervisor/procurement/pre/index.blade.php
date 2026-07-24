@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                💰 Program of Receipts and Expenditures
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Yearly budget ceilings (PPA/MFO) that PPMP plans reconcile against.
            </p>

        </div>

        @if(auth()->user()->hasPermission('create-pre'))

        <a href="{{ route('procurement.pre.create') }}"
           class="bg-gradient-to-r from-green-500 to-blue-500
                  hover:scale-105 transition
                  text-white px-5 py-3 rounded-xl shadow-lg font-semibold whitespace-nowrap">

            ➕ New PRE

        </a>

        @endif

    </div>

@if(session('success'))

<div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-lg">

{{ session('success') }}

</div>

@endif

<table class="min-w-full">

<thead>

<tr class="border-b bg-gray-100">

<th class="text-left px-4 py-3">Year</th>

<th class="text-left px-4 py-3">Status</th>

<th class="text-left px-4 py-3">Total Projected Income</th>

<th class="text-left px-4 py-3">Allocation Rows</th>

<th class="text-center px-4 py-3">Actions</th>

</tr>

</thead>

<tbody>

@forelse($pres as $pre)

<tr class="border-b">

<td class="px-4 py-3 font-semibold">{{ $pre->year }}</td>

<td class="px-4 py-3">

<span class="px-3 py-1 rounded-full text-xs font-semibold
    {{ $pre->status === 'Approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
    {{ $pre->status }}
</span>

</td>

<td class="px-4 py-3">₱ {{ number_format($pre->total_projected_income, 2) }}</td>

<td class="px-4 py-3">{{ $pre->allocations_count }}</td>

<td class="text-center px-4 py-3">

<a href="{{ route('procurement.pre.show', $pre->id) }}" class="text-blue-600 hover:underline">View</a>

@if(auth()->user()->hasPermission('delete-pre'))

|

<form id="deletePreForm{{ $pre->id }}" action="{{ route('procurement.pre.destroy', $pre->id) }}" method="POST" class="inline">
@csrf
@method('DELETE')
</form>

<button type="button" onclick="confirmDeletePre({{ $pre->id }}, {{ $pre->year }})" class="text-red-600 hover:underline">Delete</button>

@endif

</td>

</tr>

@empty

<tr>

<td colspan="5" class="text-center py-8 text-gray-500">No PRE records found</td>

</tr>

@endforelse

</tbody>

</table>

</div>

<script>

    function confirmDeletePre(id, year)
    {
        Swal.fire({
            title: 'Delete PRE for ' + year + '?',
            text: 'This removes all allocation ceilings for that year. This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deletePreForm' + id).submit();
            }
        });
    }

</script>

@endsection

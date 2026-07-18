@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">

        <div>

            @if($scopedDepartment)

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📄 My Department PPMP
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Procurement plans for {{ $scopedDepartment->department_name }}.
            </p>

            @else

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📄 Annual PPMP
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Manage your institution's annual procurement plans.
            </p>

            @endif

        </div>

        @if(auth()->user()->hasPermission('create-ppmp'))

        <a href="{{ route('procurement.plans.create') }}"
           class="bg-gradient-to-r from-green-500 to-blue-500
                  hover:scale-105 transition
                  text-white px-5 py-3 rounded-xl shadow-lg font-semibold whitespace-nowrap">

            ➕ Create New PPMP

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

<form method="GET">

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

<input
type="text"
name="search"
value="{{ request('search') }}"
placeholder="Search Plan Number or Year..."
class="border rounded px-4 py-2">

<select
name="status"
class="border rounded px-4 py-2">

<option value="">All Status</option>

<option value="Draft">Draft</option>

<option value="Submitted">Submitted</option>

<option value="Reviewed">Reviewed</option>

<option value="Approved">Approved</option>

<option value="Rejected">Rejected</option>

</select>

<button
class="bg-blue-600 text-white rounded px-4">

Search

</button>

</div>

</form>

<table class="min-w-full">

<thead>

<tr class="border-b bg-gray-100">

<th class="text-left px-4 py-3">Plan No.</th>

<th class="text-left px-4 py-3">Year</th>

<th class="text-left px-4 py-3">Status</th>

<th class="text-left px-4 py-3">Budget</th>

<th class="text-center px-4 py-3">Actions</th>

</tr>

</thead>

<tbody>

@forelse($plans as $plan)

<tr class="border-b">

<td class="px-4 py-3">

{{ $plan->plan_number }}

</td>

<td class="px-4 py-3">

{{ $plan->year }}

</td>

<td class="px-4 py-3">

@include('supervisor.procurement.plans.partials._status_badge', ['status' => $plan->status])

</td>

<td class="px-4 py-3">

₱ {{ number_format($plan->allocated_budget,2) }}

</td>

    <td class="text-center px-4 py-3">

        <a
            href="{{ route('procurement.plans.show', $plan->id) }}"
            class="text-blue-600 hover:underline">

            View

        </a>

        @if($plan->status === 'Draft')

            @if(auth()->user()->hasPermission('edit-ppmp'))

            |

            <a
                href="{{ route('procurement.plans.edit', $plan->id) }}"
                class="text-green-600 hover:underline">

                Edit

            </a>

            @endif

            @if(auth()->user()->hasPermission('delete-ppmp'))

            |

            <form
                id="deletePlanForm{{ $plan->id }}"
                action="{{ route('procurement.plans.destroy', $plan->id) }}"
                method="POST"
                class="inline">
                @csrf
                @method('DELETE')
            </form>

            <button
                type="button"
                onclick="confirmDeletePlan({{ $plan->id }}, '{{ $plan->plan_number }}')"
                class="text-red-600 hover:underline">

                Delete

            </button>

            @endif

        @endif

    </td>

</tr>

@empty

<tr>

<td colspan="5"
class="text-center py-8 text-gray-500">

No Procurement Plans Found

</td>

</tr>

@endforelse

</tbody>

</table>

<div class="mt-6">

{{ $plans->links() }}

</div>

</div>

<script>

    function confirmDeletePlan(planId, planNumber)
        {
            Swal.fire({

                title: 'Delete Procurement Plan?',

                html:
                    '<strong>' + planNumber + '</strong><br><br>' +
                    'This action cannot be undone.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#6b7280',

                confirmButtonText: 'Delete',

                cancelButtonText: 'Cancel'

            }).then((result) => {

                if (result.isConfirmed) {

                    document
                        .getElementById('deletePlanForm' + planId)
                        .submit();

                }

            });
        }

</script>

@endsection
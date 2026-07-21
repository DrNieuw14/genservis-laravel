@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- Header -->
    <div class="flex justify-between items-start mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                {{ $pageTitle ?? '👥 Employee Master' }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $pageDescription ?? 'Central repository of employee information across the GenServis ERP.' }}
            </p>

        </div>

        <div class="flex gap-2">

            @if(($isUtilityStaffPage ?? false) && auth()->user()->hasPermission('manage-utility-schedule'))
                <button type="button" onclick="openAddStaffModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow">
                    ➕ Add Staff
                </button>
            @endif

            @isset($backRoute)
                <x-back-button :href="$backRoute" />
            @endisset

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search -->
    <form method="GET" class="mb-6 flex flex-col md:flex-row gap-3">

        <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Search employee..."
            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-lg focus:border-green-500 focus:ring focus:ring-green-200">

        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow whitespace-nowrap">
            🔍 Search
        </button>

        @if($search)
            <a href="{{ url()->current() }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg shadow text-center">
                Clear
            </a>
        @endif

    </form>

    <!-- Employee Table -->

    <div class="border rounded-lg overflow-hidden">

        <div class="overflow-x-auto">

        <table class="w-full text-lg">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-6 py-3 text-left text-gray-800">Employee ID</th>

                    <th class="px-6 py-3 text-left text-gray-800">Employee</th>

                    <th class="px-6 py-3 text-left text-gray-800">Department</th>

                    <th class="px-6 py-3 text-left text-gray-800">Position</th>

                    <th class="px-6 py-3 text-left text-gray-800">Employment Status</th>

                    <th class="px-6 py-3 text-center text-gray-800">System Role</th>

                    <th class="px-6 py-3 text-center text-gray-800">Status</th>

                    <th class="px-6 py-3 text-center text-gray-800">Action</th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($employees as $employee)

                <tr class="hover:bg-gray-50">

                    <td class="px-6 py-4 font-medium">

                        {{ $employee->employee_id }}

                    </td>

                    <td class="px-6 py-4">

                        {{ $employee->fullname }}

                    </td>

                    <td class="px-6 py-4">

                        {{ $employee->departmentRecord?->department_name ?? '-' }}

                    </td>

                    <td class="px-6 py-4">

                        {{ $employee->positionRecord?->position_name ?? '-' }}

                    </td>

                    <td class="px-6 py-4">

                        {{ $employee->employmentType?->name ?? '-' }}

                    </td>

                    <td class="px-6 py-4 text-center">

                        <div class="flex flex-wrap justify-center gap-1">

                            @forelse($employee->user?->allRoles() ?? [] as $role)

                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm">
                                    {{ $role->name }}
                                </span>

                            @empty

                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm">
                                    Unassigned
                                </span>

                            @endforelse

                        </div>

                    </td>

                    <td class="px-6 py-4 text-center">

                        @if($employee->status == 'Active')

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">

                                Active

                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">

                                Inactive

                            </span>

                        @endif

                    </td>

                    <td class="px-6 py-4 text-center">

                        <a href="{{ route('employees.show', $employee) }}"
                           class="text-blue-600 hover:text-blue-800 font-medium">

                            View Profile

                        </a>

                        @if(($isUtilityStaffPage ?? false) && auth()->user()->hasPermission('manage-utility-schedule'))

                            <form method="POST" action="{{ route('employees.utility-staff.remove', $employee->id) }}"
                                  class="inline"
                                  onsubmit="return confirm('Remove {{ $employee->fullname }} from the Utility & Maintenance Staff pool?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium ml-3">
                                    Remove
                                </button>
                            </form>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="8" class="px-6 py-10 text-center text-gray-500">

                        No employees found.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

        </div>

        <div class="px-6 py-4 border-t bg-white">

            {{ $employees->links() }}

        </div>

    </div>

</div>

@if(($isUtilityStaffPage ?? false) && auth()->user()->hasPermission('manage-utility-schedule'))

    <!-- ADD STAFF MODAL -->
    <div id="addStaffModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            <div class="flex justify-between items-center border-b px-6 py-4">
                <h2 class="text-xl font-bold">Add Staff to Utility & Maintenance Pool</h2>
                <button type="button" onclick="closeAddStaffModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
            </div>

            <div class="p-6">

                <input type="text" id="staffSearchInput" placeholder="Search by name..."
                       onkeyup="searchStaffCandidates(this.value)"
                       class="w-full border rounded-lg p-3 mb-4">

                <div id="staffSearchResults" class="space-y-2 max-h-80 overflow-y-auto">
                    <p class="text-gray-500 text-sm">Start typing a name to search.</p>
                </div>

            </div>

        </div>

    </div>

    <script>

        function openAddStaffModal() {
            document.getElementById('addStaffModal').classList.remove('hidden');
            document.getElementById('staffSearchInput').value = '';
            document.getElementById('staffSearchResults').innerHTML = '<p class="text-gray-500 text-sm">Start typing a name to search.</p>';
        }

        function closeAddStaffModal() {
            document.getElementById('addStaffModal').classList.add('hidden');
        }

        let staffSearchTimeout = null;

        function searchStaffCandidates(query) {

            clearTimeout(staffSearchTimeout);

            staffSearchTimeout = setTimeout(function () {

                fetch(`{{ route('employees.utility-staff.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {

                        const container = document.getElementById('staffSearchResults');

                        if (!data.length) {
                            container.innerHTML = '<p class="text-gray-500 text-sm">No matching personnel found.</p>';
                            return;
                        }

                        container.innerHTML = data.map(function (p) {
                            return `
                                <form method="POST" action="{{ route('employees.utility-staff.add') }}"
                                      class="flex items-center justify-between border rounded-lg p-3">
                                    @csrf
                                    <input type="hidden" name="personnel_id" value="${p.id}">
                                    <div>
                                        <div class="font-semibold">${p.fullname}</div>
                                        <div class="text-xs text-gray-500">${p.position} — ${p.department}</div>
                                    </div>
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm">
                                        ➕ Add
                                    </button>
                                </form>
                            `;
                        }).join('');

                    });

            }, 300);

        }

    </script>

@endif

@endsection
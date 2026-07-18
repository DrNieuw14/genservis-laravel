@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- Header -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            👥 Employee Master
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Central repository of employee information across the GenServis ERP.
        </p>

    </div>

    <!-- Search -->
    <form method="GET" class="mb-6">

        <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Search employee..."
            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-lg focus:border-green-500 focus:ring focus:ring-green-200">

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

                    <th class="px-6 py-3 text-left text-gray-800">Employment Type</th>

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

                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm">

                            {{ $employee->user?->systemRole?->name ?? 'Unassigned' }}

                        </span>

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

@endsection
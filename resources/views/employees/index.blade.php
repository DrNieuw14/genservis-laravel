@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                👥 Employee Master
            </h1>

            <p class="text-gray-500 mt-2">
                Central repository of employee information across the GenServis ERP.
            </p>

        </div>

    </div>

    <!-- Search -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">

        <form method="GET">

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search employee..."
                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-green-500 focus:ring focus:ring-green-200">

        </form>

    </div>

    <!-- Employee Table -->

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-6 py-3 text-left">Employee ID</th>

                    <th class="px-6 py-3 text-left">Employee</th>

                    <th class="px-6 py-3 text-left">Department</th>

                    <th class="px-6 py-3 text-left">Position</th>

                    <th class="px-6 py-3 text-left">Employment Type</th>

                    <th class="px-6 py-3 text-center">System Role</th>

                    <th class="px-6 py-3 text-center">Status</th>

                    <th class="px-6 py-3 text-center">Action</th>

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

        <div class="px-6 py-4 border-t">

            {{ $employees->links() }}

        </div>

    </div>

</div>

@endsection
@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->
    <div class="mb-8">

        <h1 class="text-3xl font-bold text-white flex items-center gap-3">
            👤 User Access Management
        </h1>

        <p class="text-gray-200 mt-2">
            Manage user accounts, assign system roles, and control account access.
        </p>

    </div>

    <!-- ========================================================= -->
    <!-- SUMMARY CARDS -->
    <!-- ========================================================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <!-- Total Users -->
        <div class="bg-white rounded-2xl shadow-lg p-6">

            <p class="text-sm text-gray-500">
                👥 Total Users
            </p>

            <h2 class="text-3xl font-bold mt-2">
                {{ $totalUsers }}
            </h2>

        </div>

        <!-- Active Users -->
        <div class="bg-white rounded-2xl shadow-lg p-6">

            <p class="text-sm text-gray-500">
                🟢 Active Users
            </p>

            <h2 class="text-3xl font-bold text-green-600 mt-2">
                {{ $activeUsers }}
            </h2>

        </div>

        <!-- Pending Users -->
        <div class="bg-white rounded-2xl shadow-lg p-6">

            <p class="text-sm text-gray-500">
                🟡 Pending Users
            </p>

            <h2 class="text-3xl font-bold text-yellow-500 mt-2">
                {{ $pendingUsers }}
            </h2>

        </div>

        <!-- System Roles -->
        <div class="bg-white rounded-2xl shadow-lg p-6">

            <p class="text-sm text-gray-500">
                🛡️ System Roles
            </p>

            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                {{ $roles->count() }}
            </h2>

        </div>

    </div>

    <!-- ========================================================= -->
    <!-- SEARCH -->
    <!-- ========================================================= -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">

        <form
            method="GET"
            action="{{ route('admin.user-access.index') }}">

            <div class="flex flex-col md:flex-row gap-4">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search employee name, username or employee ID..."
                    class="flex-1 rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">

                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">

                    🔍 Search

                </button>

            </div>

        </form>

    </div>

    <!-- ========================================================= -->
    <!-- USER TABLE -->
    <!-- ========================================================= -->

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-green-700 text-white">

                <tr>

                    <th class="px-6 py-3 text-left">Employee</th>

                    <th class="px-6 py-3 text-left">
                        Username
                    </th>

                    <th class="px-6 py-3 text-left">
                        Department
                    </th>

                    <th class="px-6 py-3 text-left">
                        Position
                    </th>

                    <th class="px-6 py-3 text-left">
                        System Role
                    </th>

                    <th class="px-6 py-3 text-left">
                        Status
                    </th>

                    <th class="px-6 py-3 text-center">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($users as $user)

                <tr class="border-b hover:bg-gray-50">

                    <!-- Employee -->
                    <td class="px-6 py-4">

                        <div class="font-semibold text-gray-800">

                            {{ optional($user->personnel)->fullname ?? $user->name }}

                        </div>

                        <div class="text-sm text-gray-500">

                            {{ optional($user->personnel)->employee_id ?? 'No Employee ID' }}

                        </div>

                    </td>

                    <!-- Username -->
                    <td class="px-6 py-4">

                        {{ $user->username }}

                    </td>

                    <!-- Department -->
                    <td class="px-6 py-4">

                        {{ optional(optional($user->personnel)->departmentRecord)->department_name ?? '-' }}

                    </td>

                    <!-- Position -->
                    <td class="px-6 py-4">

                        {{ optional(optional($user->personnel)->positionRecord)->position_name ?? '-' }}

                    </td>

                    <!-- Role -->
                    <td class="px-6 py-4">

                        @if($user->systemRole)

                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">

                                {{ $user->systemRole->name }}

                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs">

                                Unassigned

                            </span>

                        @endif

                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4">

                        @include('admin.user-access.partials.status-badge', ['status' => $user->status])

                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4">

                        <div class="flex flex-wrap justify-center gap-2">

                            <!-- Details -->
                            <a
                                href="{{ route('admin.user-access.show', $user) }}"
                                class="inline-flex items-center gap-2 px-4 py-2
                                    bg-indigo-600 hover:bg-indigo-700
                                    text-white text-sm font-medium
                                    rounded-lg shadow transition">

                                🔍 Details

                            </a>

                            <!-- Assign Role -->
                            <a
                                href="{{ route('admin.user-access.edit', $user) }}"
                                class="inline-flex items-center gap-2 px-4 py-2
                                    bg-orange-500 hover:bg-orange-600
                                    text-white text-sm font-medium
                                    rounded-lg shadow transition">

                                ✏️ Assign Role

                            </a>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7"
                        class="text-center py-10 text-gray-500">

                        No users found.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        <div class="p-6">

            {{ $users->links() }}

        </div>

    </div>

</div>

@endsection
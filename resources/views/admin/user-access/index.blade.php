@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- PAGE HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            👤 User Access Management
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Manage user accounts, assign system roles, and control account access.
        </p>

    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

        <!-- Total Users -->
        <div class="bg-blue-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">👥 Total Users</div>
            <div class="text-4xl font-bold">{{ $totalUsers }}</div>
        </div>

        <!-- Active Users -->
        <div class="bg-green-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">🟢 Active Users</div>
            <div class="text-4xl font-bold">{{ $activeUsers }}</div>
        </div>

        <!-- Pending Users -->
        <div class="bg-yellow-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">🟡 Pending Users</div>
            <div class="text-4xl font-bold">{{ $pendingUsers }}</div>
        </div>

        <!-- System Roles -->
        <div class="bg-purple-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">🛡️ System Roles</div>
            <div class="text-4xl font-bold">{{ $roles->count() }}</div>
        </div>

    </div>

    <!-- SEARCH -->
    <form
        method="GET"
        action="{{ route('admin.user-access.index') }}"
        class="mb-6">

        <div class="flex flex-col md:flex-row gap-4">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search employee name, username or employee ID..."
                class="flex-1 rounded-lg border border-gray-300 px-4 py-3 text-lg focus:ring-green-500 focus:border-green-500">

            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition font-semibold text-lg">

                🔍 Search

            </button>

        </div>

    </form>

    <!-- USER TABLE -->
    <div class="border rounded-lg overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-lg">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-6 py-3 text-left text-gray-800">Employee</th>

                        <th class="px-6 py-3 text-left text-gray-800">
                            Username
                        </th>

                        <th class="px-6 py-3 text-left text-gray-800">
                            Department
                        </th>

                        <th class="px-6 py-3 text-left text-gray-800">
                            Position
                        </th>

                        <th class="px-6 py-3 text-left text-gray-800">
                            System Role
                        </th>

                        <th class="px-6 py-3 text-left text-gray-800">
                            Status
                        </th>

                        <th class="px-6 py-3 text-center text-gray-800">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200">

                @forelse($users as $user)

                    <tr class="hover:bg-gray-50">

                        <!-- Employee -->
                        <td class="px-6 py-4">

                            <div class="font-semibold text-gray-800">

                                {{ optional($user->personnel)->fullname ?? $user->name }}

                            </div>

                            <div class="text-base text-gray-500">

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

                            <div class="flex flex-wrap gap-1">

                                @forelse($user->allRoles() as $role)

                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-base font-semibold">

                                        {{ $role->name }}

                                    </span>

                                @empty

                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-base">

                                        Unassigned

                                    </span>

                                @endforelse

                            </div>

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
                                        text-white text-base font-medium
                                        rounded-lg shadow transition">

                                    🔍 Details

                                </a>

                                <!-- Assign Role -->
                                <a
                                    href="{{ route('admin.user-access.edit', $user) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2
                                        bg-orange-500 hover:bg-orange-600
                                        text-white text-base font-medium
                                        rounded-lg shadow transition">

                                    ✏️ Assign Role

                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="text-center py-10 text-gray-500 text-lg">

                            No users found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-6 bg-white border-t">

            {{ $users->links() }}

        </div>

    </div>

</div>

@endsection

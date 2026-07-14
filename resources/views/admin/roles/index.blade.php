@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                🔐 Role Management
            </h1>

            <p class="text-gray-500 mt-2">
                Manage system roles and access levels.
            </p>
        </div>

        <a href="{{ route('roles.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow">

            + Create Role

        </a>

    </div>

    @if(session('success'))

        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6">

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">

            {{ session('error') }}

        </div>

    @endif

    <div class="bg-white rounded-xl shadow p-6">

        <form method="GET">

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search roles..."
                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-green-500 focus:ring focus:ring-green-200">

        </form>

    </div>

    <div class="bg-white rounded-xl shadow mt-6 overflow-hidden">

        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-6 py-3 text-left">
                        Role
                    </th>

                    <th class="px-6 py-3 text-left">
                        Description
                    </th>

                    <th class="px-6 py-3 text-center">
                        Users
                    </th>

                    <th class="px-6 py-3 text-center">
                        Status
                    </th>

                    <th class="px-6 py-3 text-center">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($roles as $role)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 font-medium">
                            {{ $role->name }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $role->description ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            {{ $role->users_count }}
                        </td>

                        <td class="px-6 py-4 text-center">

                            @if($role->status)

                                <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700">
                                    Active
                                </span>

                            @else

                                <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-700">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4 text-center">

                            <div class="flex justify-center items-center gap-4">

                                <!-- Edit -->
                                <a href="{{ route('roles.edit', $role) }}"
                                   class="text-blue-600 hover:text-blue-800 font-medium">

                                    Edit

                                </a>

                                <!-- Permissions -->
                                <a href="{{ route('roles.permissions', $role) }}"
                                   class="text-green-600 hover:text-green-800 font-medium">

                                    Permissions

                                </a>

                                @if(in_array($role->name, ['Super Administrator', 'System Administrator']))

                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-medium">

                                        System Role

                                    </span>

                                @else

                                    <form action="{{ route('roles.toggle-status', $role) }}"
                                          method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Are you sure you want to {{ $role->status ? 'deactivate' : 'activate' }} this role?')"
                                            class="{{ $role->status
                                                ? 'text-red-600 hover:text-red-800'
                                                : 'text-green-600 hover:text-green-800' }} font-medium">

                                            {{ $role->status ? 'Deactivate' : 'Activate' }}

                                        </button>

                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="px-6 py-10 text-center text-gray-500">

                            No roles found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

        <div class="px-6 py-4 border-t">

            {{ $roles->links() }}

        </div>

    </div>

</div>

@endsection
@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🔐 Role Management
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Manage system roles and access levels.
            </p>
        </div>

        <a href="{{ route('roles.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow font-semibold text-lg text-center">

            + Create Role

        </a>

    </div>

    @if(session('success'))

        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6 text-lg">

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6 text-lg">

            {{ session('error') }}

        </div>

    @endif

    <!-- SEARCH -->
    <form method="GET" class="mb-6">

        <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Search roles..."
            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-lg focus:border-green-500 focus:ring focus:ring-green-200">

    </form>

    <!-- TABLE -->
    <div class="border rounded-lg overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-lg">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-6 py-4 text-left text-gray-800">
                            Role
                        </th>

                        <th class="px-6 py-4 text-left text-gray-800">
                            Description
                        </th>

                        <th class="px-6 py-4 text-center text-gray-800">
                            Users
                        </th>

                        <th class="px-6 py-4 text-center text-gray-800">
                            Status
                        </th>

                        <th class="px-6 py-4 text-center text-gray-800">
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

                                    <span class="px-3 py-1 text-base rounded-full bg-green-100 text-green-700 font-semibold">
                                        Active
                                    </span>

                                @else

                                    <span class="px-3 py-1 text-base rounded-full bg-red-100 text-red-700 font-semibold">
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
                                            class="px-2 py-1 text-sm rounded-full bg-gray-100 text-gray-600 font-medium">

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
                                class="px-6 py-10 text-center text-gray-500 text-lg">

                                No roles found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="px-6 py-4 border-t bg-white">

            {{ $roles->links() }}

        </div>

    </div>

</div>

@endsection

@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                🔐 Role Permission Matrix
            </h1>

            <p class="text-gray-500 mt-2">
                Manage permissions for
                <span class="font-semibold">{{ $role->name }}</span>
            </p>
        </div>

        <x-back-button :href="route('roles.index')">Back to Roles</x-back-button>

    </div>

    @if(session('success'))

        <div class="mb-6 rounded-lg border border-green-300 bg-green-100 px-4 py-3 text-green-700">

            {{ session('success') }}

        </div>

    @endif

    <form method="POST"
          action="{{ route('roles.permissions.update', $role) }}">

        @csrf

        @foreach($permissions as $module => $modulePermissions)

            <div class="bg-white rounded-xl shadow mb-8">

                <div class="px-6 py-4 border-b bg-gray-50">

                    <h2 class="text-lg font-bold text-gray-800">

                        {{ $module }}

                    </h2>

                </div>

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                        @foreach($modulePermissions as $permission)

                            <label
                                class="flex items-center gap-3 p-3 rounded-lg border hover:bg-green-50 cursor-pointer">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->id }}"

                                    {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}

                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500">

                                <div>

                                    <div class="font-medium text-gray-800">

                                        {{ $permission->name }}

                                    </div>

                                    <div class="text-xs text-gray-500">

                                        {{ $permission->slug }}

                                    </div>

                                </div>

                            </label>

                        @endforeach

                    </div>

                </div>

            </div>

        @endforeach

        <div class="flex justify-end">

            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg shadow">

                💾 Save Permissions

            </button>

        </div>

    </form>

</div>

@endsection
@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- PAGE HEADER -->
    <div class="flex justify-between items-start mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ✏️ Assign Roles
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ optional($user->personnel)->fullname ?? $user->name }}
                ({{ $user->username }})
            </p>

        </div>

        <div class="flex items-center gap-3">

            @if(auth()->user()->hasPermission('manage-roles'))

                <button
                    type="button"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'create-role')"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow font-semibold text-lg text-center">

                    + Add New Role

                </button>

            @endif

            <x-back-button :href="route('admin.user-access.show', $user)" />

        </div>

    </div>

    <div class="mb-6">

            <p class="text-sm text-gray-500">Current Roles</p>

            <div class="mt-1 flex flex-wrap gap-2">

                @forelse($user->allRoles() as $role)

                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                        {{ $role->name }}
                    </span>

                @empty

                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs">
                        Unassigned
                    </span>

                @endforelse

            </div>

        </div>

        <form
            method="POST"
            action="{{ route('admin.user-access.update', $user) }}">

            @csrf
            @method('PUT')

            <div>

                <label class="block text-sm font-medium text-gray-700">
                    Primary System Role
                </label>

                <p class="text-sm text-gray-500 mt-1 mb-2">
                    The account's main role — used as its default badge across the system.
                </p>

                <select
                    name="role_id"
                    class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                    required>

                    <option value="" disabled {{ old('role_id', $user->role_id) ? '' : 'selected' }}>
                        -- Select a Role --
                    </option>

                    @foreach($roles as $role)

                        <option
                            value="{{ $role->id }}"
                            {{ (int) old('role_id', $user->role_id) === $role->id ? 'selected' : '' }}>

                            {{ $role->name }}

                        </option>

                    @endforeach

                </select>

                @error('role_id')

                    <p class="text-sm text-red-600 mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            <div class="mt-8">

                <label class="block text-sm font-medium text-gray-700">
                    Additional Roles
                </label>

                <p class="text-sm text-gray-500 mt-1 mb-3">
                    Optional — stack more roles on top of the primary one when an account genuinely
                    covers more than one function (e.g. both General Services Officer and Property
                    Custodian). The account gets every permission from every role checked here.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 border rounded-lg p-4 bg-gray-50">

                    @php
                        $selectedAdditional = collect(
                            old('additional_role_ids', $user->additionalRoles->pluck('id')->all())
                        )->map(fn ($id) => (int) $id);
                    @endphp

                    @foreach($roles as $role)

                        <label class="flex items-center gap-2 text-sm text-gray-700">

                            <input
                                type="checkbox"
                                name="additional_role_ids[]"
                                value="{{ $role->id }}"
                                {{ $selectedAdditional->contains($role->id) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-green-600 focus:ring-green-500">

                            {{ $role->name }}

                        </label>

                    @endforeach

                </div>

                @error('additional_role_ids')

                    <p class="text-sm text-red-600 mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            <div class="flex justify-end mt-8">

                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg shadow">

                    💾 Save Role Assignment

                </button>

            </div>

        </form>

        @if(auth()->user()->hasPermission('manage-roles'))

            <x-modal name="create-role" :show="$errors->createRole->isNotEmpty()" focusable>

                <form method="POST" action="{{ route('roles.store') }}" class="p-6">

                    @csrf

                    <input type="hidden" name="redirect_to" value="{{ request()->getRequestUri() }}">
                    <input type="hidden" name="status" value="1">

                    <h2 class="text-lg font-medium text-gray-900">
                        Add New Role
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Creates the role immediately so you can select it below without leaving this page.
                    </p>

                    <div class="mt-6">

                        <x-input-label for="new_role_name" value="Role Name" />

                        <x-text-input
                            id="new_role_name"
                            name="name"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('name')"
                            required
                            autofocus />

                        <x-input-error :messages="$errors->createRole->get('name')" class="mt-2" />

                    </div>

                    <div class="mt-6">

                        <x-input-label for="new_role_description" value="Description (optional)" />

                        <textarea
                            id="new_role_description"
                            name="description"
                            rows="2"
                            class="w-full mt-1 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('description') }}</textarea>

                        <x-input-error :messages="$errors->createRole->get('description')" class="mt-2" />

                    </div>

                    <div class="mt-6 flex justify-end gap-3">

                        <x-secondary-button x-on:click="$dispatch('close')">
                            Cancel
                        </x-secondary-button>

                        <x-primary-button>
                            Create Role
                        </x-primary-button>

                    </div>

                </form>

            </x-modal>

        @endif

</div>

@endsection

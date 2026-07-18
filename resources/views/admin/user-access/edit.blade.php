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

        <x-back-button :href="route('admin.user-access.show', $user)" />

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

</div>

@endsection

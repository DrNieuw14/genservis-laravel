@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->
    <div class="flex justify-between items-start mb-8">

        <div>

            <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                ✏️ Assign Role
            </h1>

            <p class="text-gray-200 mt-2">
                {{ optional($user->personnel)->fullname ?? $user->name }}
                ({{ $user->username }})
            </p>

        </div>

        <a
            href="{{ route('admin.user-access.show', $user) }}"
            class="inline-flex items-center gap-2 px-4 py-2
                bg-indigo-600 hover:bg-indigo-700
                text-white text-sm font-medium
                rounded-lg shadow transition">

            ⬅️ Back

        </a>

    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">

        <div class="mb-6">

            <p class="text-sm text-gray-500">Current System Role</p>

            <div class="mt-1">

                @if($user->systemRole)

                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                        {{ $user->systemRole->name }}
                    </span>

                @else

                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs">
                        Unassigned
                    </span>

                @endif

            </div>

        </div>

        <form
            method="POST"
            action="{{ route('admin.user-access.update', $user) }}">

            @csrf
            @method('PUT')

            <div>

                <label class="block text-sm font-medium text-gray-700">
                    Select System Role
                </label>

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

            <div class="flex justify-end mt-8">

                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg shadow">

                    💾 Save Role Assignment

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

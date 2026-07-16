@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->
    <div class="flex justify-between items-start mb-8">

        <div>

            <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                👤 {{ optional($user->personnel)->fullname ?? $user->name }}
            </h1>

            <p class="text-gray-200 mt-2">
                User account details and role permissions.
            </p>

        </div>

        <div class="flex gap-2">

            <!-- Back -->
            <a
                href="{{ route('admin.user-access.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2
                    bg-indigo-600 hover:bg-indigo-700
                    text-white text-sm font-medium
                    rounded-lg shadow transition">

                ⬅️ Back

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

    </div>

    @if(session('success'))

        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-8">

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-8">

            {{ session('error') }}

        </div>

    @endif

    <!-- ========================================================= -->
    <!-- EMPLOYEE INFORMATION -->
    <!-- ========================================================= -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">

        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            🧾 Employee Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div>
                <p class="text-sm text-gray-500">Employee ID</p>
                <h3 class="font-semibold mt-1">
                    {{ optional($user->personnel)->employee_id ?? '-' }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-gray-500">Full Name</p>
                <h3 class="font-semibold mt-1">
                    {{ optional($user->personnel)->fullname ?? $user->name }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-gray-500">Department</p>
                <h3 class="font-semibold mt-1">
                    {{ optional(optional($user->personnel)->departmentRecord)->department_name ?? '-' }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-gray-500">Position</p>
                <h3 class="font-semibold mt-1">
                    {{ optional(optional($user->personnel)->positionRecord)->position_name ?? '-' }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-gray-500">Employment Type</p>
                <h3 class="font-semibold mt-1">
                    {{ optional(optional($user->personnel)->employmentType)->name ?? '-' }}
                </h3>
            </div>

        </div>

    </div>

    <!-- ========================================================= -->
    <!-- ACCOUNT INFORMATION -->
    <!-- ========================================================= -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">

        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            🔑 Account Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div>
                <p class="text-sm text-gray-500">Username</p>
                <h3 class="font-semibold mt-1">
                    {{ $user->username }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-gray-500">Email</p>
                <h3 class="font-semibold mt-1">
                    {{ $user->email }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status</p>

                <div class="mt-1">

                    @include('admin.user-access.partials.status-badge', ['status' => $user->status])

                </div>

            </div>

            <div>
                <p class="text-sm text-gray-500">System Role</p>

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

        </div>

    </div>

    <!-- ========================================================= -->
    <!-- ACCOUNT STATUS -->
    <!-- ========================================================= -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">

        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            🔒 Account Status
        </h2>

        <div class="flex items-center justify-between flex-wrap gap-4">

            <div>

                <p class="text-sm text-gray-500 mb-2">Current Status</p>

                @include('admin.user-access.partials.status-badge', ['status' => $user->status])

            </div>

            <div class="flex flex-wrap gap-2">

                @if($user->status === 'pending')

                    @if($user->personnel)

                        <form method="POST" action="{{ route('admin.user-access.update-status', $user) }}">

                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="status" value="approved">

                            <button
                                type="submit"
                                onclick="return confirm('Activate this account?')"
                                class="inline-flex items-center gap-2 px-4 py-2
                                    bg-green-600 hover:bg-green-700
                                    text-white text-sm font-medium
                                    rounded-lg shadow transition">

                                ✅ Activate

                            </button>

                        </form>

                    @else

                        <a
                            href="{{ route('admin.users.onboarding', $user) }}"
                            class="inline-flex items-center gap-2 px-4 py-2
                                bg-green-600 hover:bg-green-700
                                text-white text-sm font-medium
                                rounded-lg shadow transition">

                            📝 Complete Onboarding

                        </a>

                    @endif

                @elseif($user->status === 'approved')

                    <form method="POST" action="{{ route('admin.user-access.update-status', $user) }}">

                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status" value="suspended">

                        <button
                            type="submit"
                            onclick="return confirm('Suspend this account? The user will not be able to log in.')"
                            class="inline-flex items-center gap-2 px-4 py-2
                                bg-red-600 hover:bg-red-700
                                text-white text-sm font-medium
                                rounded-lg shadow transition">

                            ⛔ Suspend

                        </button>

                    </form>

                    <form method="POST" action="{{ route('admin.user-access.update-status', $user) }}">

                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status" value="locked">

                        <button
                            type="submit"
                            onclick="return confirm('Lock this account? The user will not be able to log in.')"
                            class="inline-flex items-center gap-2 px-4 py-2
                                bg-red-600 hover:bg-red-700
                                text-white text-sm font-medium
                                rounded-lg shadow transition">

                            🔒 Lock

                        </button>

                    </form>

                    <form method="POST" action="{{ route('admin.user-access.update-status', $user) }}">

                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status" value="inactive">

                        <button
                            type="submit"
                            onclick="return confirm('Deactivate this account? The user will not be able to log in.')"
                            class="inline-flex items-center gap-2 px-4 py-2
                                bg-red-600 hover:bg-red-700
                                text-white text-sm font-medium
                                rounded-lg shadow transition">

                            🚫 Deactivate

                        </button>

                    </form>

                @elseif(in_array($user->status, ['suspended', 'locked', 'inactive']))

                    <form method="POST" action="{{ route('admin.user-access.update-status', $user) }}">

                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status" value="approved">

                        <button
                            type="submit"
                            onclick="return confirm('Reactivate this account?')"
                            class="inline-flex items-center gap-2 px-4 py-2
                                bg-green-600 hover:bg-green-700
                                text-white text-sm font-medium
                                rounded-lg shadow transition">

                            ✅ Reactivate

                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

    <!-- ========================================================= -->
    <!-- PERMISSIONS -->
    <!-- ========================================================= -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">

        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            🛡️ Permissions
        </h2>

        @if($user->systemRole && $permissions->isNotEmpty())

            <div class="space-y-6">

                @foreach($permissions as $module => $modulePermissions)

                    <div>

                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">
                            {{ $module }}
                        </h3>

                        <div class="flex flex-wrap gap-2">

                            @foreach($modulePermissions as $permission)

                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                    {{ $permission->name }}
                                </span>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <p class="text-gray-500">
                This user has no role assigned, so no permissions are granted.
            </p>

        @endif

    </div>

</div>

@endsection

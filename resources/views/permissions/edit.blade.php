@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-start mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ✏️ Edit Permission
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $permission->module }}
            </p>

        </div>

        <x-back-button :href="route('permissions.index')" />

    </div>

    @if(session('error'))

        <div class="mb-6 rounded-lg border border-red-300 bg-red-100 px-4 py-3 text-red-700 text-lg">

            {{ session('error') }}

        </div>

    @endif

    <form method="POST" action="{{ route('permissions.update', $permission->id) }}">

            @csrf
            @method('PUT')

            <div class="space-y-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Name
                    </label>

                    <input
                        type="text"
                        value="{{ $permission->name }}"
                        readonly
                        class="w-full mt-2 rounded-lg border-gray-300 bg-gray-100 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Slug
                    </label>

                    <input
                        type="text"
                        value="{{ $permission->slug }}"
                        readonly
                        class="w-full mt-2 rounded-lg border-gray-300 bg-gray-100 text-gray-600 font-mono">

                    <p class="text-sm text-gray-500 mt-2">
                        The slug can't be changed here - it's referenced directly by route middleware and
                        permission checks throughout the app, so editing it would silently break access control.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Module
                    </label>

                    <input
                        type="text"
                        value="{{ $permission->module }}"
                        readonly
                        class="w-full mt-2 rounded-lg border-gray-300 bg-gray-100 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="3"
                        class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('description', $permission->description) }}</textarea>
                </div>

                <div>
                    <input type="hidden" name="status" value="0">

                    <label class="inline-flex items-center gap-2">

                        <input
                            type="checkbox"
                            name="status"
                            value="1"
                            {{ old('status', $permission->status) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">

                        <span class="text-sm font-medium text-gray-700">
                            Active
                        </span>

                    </label>

                    <p class="text-sm text-gray-500 mt-2">
                        Turning this off blocks the permission for every role that has it, immediately.
                    </p>
                </div>

            </div>

            <div class="flex justify-end mt-8">

                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg shadow">

                    💾 Save Changes

                </button>

            </div>

        </form>

</div>

@endsection

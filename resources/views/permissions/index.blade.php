@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-start mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🛡️ Permission Management
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Every permission slug defined in the system, grouped by module. Status can be toggled here;
                the slug itself is fixed since it's referenced throughout the app's access control.
            </p>

        </div>

        <x-back-button :href="route('dashboard')" />

    </div>

    @if(session('success'))

        <div class="mb-6 rounded-lg border border-green-300 bg-green-100 px-4 py-3 text-green-700 text-lg">

            {{ session('success') }}

        </div>

    @endif

    @foreach($permissions as $module => $modulePermissions)

        <div class="mb-8 last:mb-0">

            <h3 class="text-xl font-semibold text-gray-800 mb-3">
                {{ $module }}
            </h3>

            <div class="border rounded-lg overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="w-full text-lg">

                        <thead class="bg-gray-100">

                            <tr>

                                <th class="px-6 py-3 text-left text-gray-800">Name</th>

                                <th class="px-6 py-3 text-left text-gray-800">Slug</th>

                                <th class="px-6 py-3 text-left text-gray-800">Description</th>

                                <th class="px-6 py-3 text-center text-gray-800">Status</th>

                                <th class="px-6 py-3 text-center text-gray-800">Action</th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            @foreach($modulePermissions as $permission)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-3 font-medium text-gray-800">

                                        {{ $permission->name }}

                                    </td>

                                    <td class="px-6 py-3 text-base text-gray-500 font-mono">

                                        {{ $permission->slug }}

                                    </td>

                                    <td class="px-6 py-3 text-base text-gray-600">

                                        {{ $permission->description ?? '—' }}

                                    </td>

                                    <td class="px-6 py-3 text-center">

                                        @if($permission->status)

                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-base font-semibold">Active</span>

                                        @else

                                            <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-base font-semibold">Inactive</span>

                                        @endif

                                    </td>

                                    <td class="px-6 py-3 text-center">

                                        <a
                                            href="{{ route('permissions.edit', $permission->id) }}"
                                            class="text-blue-600 hover:underline font-medium">

                                            Edit

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endforeach

</div>

@endsection

@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-r from-cyan-700 to-blue-900 p-10">

    <!-- HEADER -->

    <div class="mb-8">

        <h1 class="text-4xl font-bold text-white flex items-center gap-3">
            🏢 Departments
        </h1>

        <p class="text-gray-200 mt-2">
            Manage institutional departments for GenServis.
        </p>

    </div>

    <!-- ACTION BUTTON -->

    <div class="mb-6">

        <a href="{{ route('supervisor.departments.create') }}"
           class="bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg transition duration-300">

            ➕ Add Department

        </a>

    </div>

    <!-- TABLE CARD -->

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="p-6">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b text-gray-700">

                            <th class="text-left py-4 px-4 font-semibold">
                                ID
                            </th>

                            <th class="text-left py-4 px-4 font-semibold">
                                Department
                            </th>

                            <th class="text-left py-4 px-4 font-semibold">
                                Code
                            </th>

                            <th class="text-left py-4 px-4 font-semibold">
                                Description
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($departments as $department)

                        <tr class="border-b hover:bg-gray-50 transition">

                            <td class="py-4 px-4">
                                {{ $department->id }}
                            </td>

                            <td class="py-4 px-4 font-semibold text-gray-800">
                                {{ $department->department_name }}
                            </td>

                            <td class="py-4 px-4">

                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">

                                    {{ $department->department_code }}

                                </span>

                            </td>

                            <td class="py-4 px-4 text-gray-600">
                                {{ $department->description }}
                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="4"
                                class="text-center py-10 text-gray-400">

                                No departments found.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
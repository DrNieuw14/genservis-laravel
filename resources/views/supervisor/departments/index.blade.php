@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🏢 Departments
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Manage institutional departments for GenServis.
            </p>

        </div>

        <a href="{{ route('supervisor.departments.create') }}"
           class="bg-gradient-to-r from-green-500 to-blue-500
                  hover:scale-105 transition
                  text-white px-5 py-3 rounded-xl shadow-lg font-semibold">

            ➕ Add Department

        </a>

    </div>

    <!-- TABLE -->
    <div class="border rounded-lg overflow-hidden">

        <table class="w-full text-lg">

            <!-- HEADER -->
            <thead class="bg-gray-100">

                <tr>

                    <th class="p-4 text-left text-gray-800">#</th>

                    <th class="p-4 text-left text-gray-800">
                        Department
                    </th>

                    <th class="p-4 text-left text-gray-800">
                        Code
                    </th>

                    <th class="p-4 text-left text-gray-800">
                        Description
                    </th>

                    <th class="p-4 text-center text-gray-800">
                        Actions
                    </th>

                </tr>

            </thead>

            <!-- BODY -->
            <tbody class="divide-y divide-gray-200">

                @forelse($departments as $department)

                <tr class="hover:bg-gray-50 transition">

                    <!-- ID -->
                    <td class="p-4 font-semibold text-gray-700">
                        {{ $department->id }}
                    </td>

                    <!-- NAME -->
                    <td class="p-4 font-semibold text-gray-800">
                        {{ $department->department_name }}
                    </td>

                    <!-- CODE -->
                    <td class="p-4">

                        <span class="bg-blue-100 text-blue-700
                                     px-3 py-1 rounded-full
                                     text-sm font-semibold
                                     whitespace-nowrap">

                            {{ $department->department_code }}

                        </span>

                    </td>

                    <!-- DESCRIPTION -->
                    <td class="p-4 text-gray-700">
                        {{ $department->description }}
                    </td>

                    <!-- ACTIONS -->
                    <td class="p-4 text-center">

                        <div class="flex justify-center gap-2">

                            <!-- EDIT -->
                            <a href="{{ route('supervisor.departments.edit', $department->id) }}"
                               class="bg-blue-500 hover:bg-blue-600
                                      text-white px-4 py-2 rounded-lg shadow">

                                ✏️ Edit

                            </a>

                            <!-- DELETE -->
                            <form action="{{ route('supervisor.departments.destroy', $department->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this department?')">

                                @csrf
                                @method('DELETE')

                                <button class="bg-red-500 hover:bg-red-600
                                               text-white px-4 py-2 rounded-lg shadow">

                                    🗑 Delete

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5"
                        class="text-center p-10 text-gray-500">

                        No departments found.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

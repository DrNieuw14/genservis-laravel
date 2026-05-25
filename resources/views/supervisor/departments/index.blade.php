@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-4xl font-bold text-white flex items-center gap-3">
                🏢 Departments
            </h2>

            <p class="text-white/80 mt-2">
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

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        <table class="w-full">

            <!-- HEADER -->
            <thead class="bg-gradient-to-r from-green-500 to-blue-500 text-white">

                <tr>

                    <th class="p-4 text-left">#</th>

                    <th class="p-4 text-left">
                        Department
                    </th>

                    <th class="p-4 text-left">
                        Code
                    </th>

                    <th class="p-4 text-left">
                        Description
                    </th>

                    <th class="p-4 text-center">
                        Actions
                    </th>

                </tr>

            </thead>

            <!-- BODY -->
            <tbody>

                @forelse($departments as $department)

                <tr class="border-b hover:bg-gray-50 transition">

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
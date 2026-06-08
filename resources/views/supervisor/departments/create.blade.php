@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-r from-cyan-700 to-blue-900 p-10">

    <div class="max-w-4xl mx-auto">

        <!-- HEADER -->
        <div class="mb-8">

            <h1 class="text-4xl font-bold text-white flex items-center gap-3">
                ➕ Add Department
            </h1>

            <p class="text-gray-200 mt-2">
                Create and manage departments for GenServis.
            </p>

        </div>

        <!-- FORM CARD -->
        

<div class="bg-white rounded-3xl shadow-2xl p-8 max-w-4xl mx-auto">

        <form action="{{ route('supervisor.departments.store') }}"
              method="POST">

            @csrf

            <!-- Department Name -->

            <div class="mb-6">

                <label class="block text-gray-700 font-semibold mb-2">

                    Department Name

                </label>

                <input type="text"
                       name="department_name"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none"
                       placeholder="Enter department name"
                       required>

            </div>

            <!-- Department Code -->

            <div class="mb-6">

                <label class="block text-gray-700 font-semibold mb-2">

                    Department Code

                </label>

                <input type="text"
                       name="department_code"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none"
                       placeholder="Example: ICT">

            </div>

            <!-- Description -->

            <div class="mb-8">

                <label class="block text-gray-700 font-semibold mb-2">

                    Description

                </label>

                <textarea name="description"
                          rows="4"
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none"
                          placeholder="Department description"></textarea>

            </div>

            <!-- BUTTONS -->

            <div class="flex gap-4">

                <button type="submit"
                        class="bg-gradient-to-r from-green-500 to-blue-600 hover:opacity-90 text-white px-6 py-3 rounded-xl shadow-lg transition">

                    💾 Save Department

                </button>

                <a href="{{ route('supervisor.departments.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-xl">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

@endsection
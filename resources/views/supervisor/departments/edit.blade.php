@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            ✏️ Edit Department
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Update department information.
        </p>

    </div>

            <form action="{{ route('supervisor.departments.update', $department->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <!-- Department Name -->

                <div class="mb-6">

                    <label class="block text-gray-700 font-semibold mb-2">
                        Department Name
                    </label>

                    <input type="text"
                           name="department_name"
                           value="{{ $department->department_name }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3">

                </div>

                <!-- Department Code -->

                <div class="mb-6">

                    <label class="block text-gray-700 font-semibold mb-2">
                        Department Code
                    </label>

                    <input type="text"
                           name="department_code"
                           value="{{ $department->department_code }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3">

                </div>

                <!-- Description -->

                <div class="mb-8">

                    <label class="block text-gray-700 font-semibold mb-2">
                        Description
                    </label>

                    <textarea name="description"
                              rows="4"
                              class="w-full border border-gray-300 rounded-xl px-4 py-3">{{ $department->description }}</textarea>

                </div>

                <!-- Buttons -->

                <div class="flex gap-4">

                    <button type="submit"
                            class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-6 py-3 rounded-xl">

                        💾 Update Department

                    </button>

                    <a href="{{ route('supervisor.departments.index') }}"
                       class="bg-gray-300 px-6 py-3 rounded-xl">

                        Cancel

                    </a>

                </div>

            </form>

</div>

@endsection
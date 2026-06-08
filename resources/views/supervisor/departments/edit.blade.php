@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-r from-cyan-700 to-blue-900 p-10">

    <div class="max-w-5xl mx-auto">

        <!-- HEADER -->

        <div class="mb-8 text-center">

            <h1 class="text-4xl font-bold text-white flex items-center justify-center gap-3">
                ✏️ Edit Department
            </h1>

            <p class="text-gray-200 mt-2">
                Update department information.
            </p>

        </div>

        <!-- FORM CARD -->

        <div class="bg-white rounded-3xl shadow-2xl p-8">

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

    </div>

</div>

@endsection
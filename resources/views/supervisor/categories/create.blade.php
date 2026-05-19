@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">

        <h2 class="text-4xl font-bold text-white flex items-center gap-3">
            ➕ Add Category
        </h2>

        <p class="text-white/80 mt-2">
            Create a new inventory category for GenServis.
        </p>

    </div>

    <!-- ERROR ALERT -->
    @if ($errors->any())

        <div class="bg-red-500 text-white p-4 rounded-xl mb-4 shadow">

            <ul class="list-disc list-inside">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <!-- FORM CARD -->
    <div class="bg-white rounded-2xl shadow-2xl p-8">

        <form action="{{ route('categories.store') }}" method="POST">

            @csrf

            <!-- CATEGORY NAME -->
            <div class="mb-6">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Category Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Example: Office Supplies"
                       class="w-full border rounded-xl p-4 focus:ring-2 focus:ring-blue-400"
                       required>

            </div>

            <!-- BUTTONS -->
            <div class="flex gap-3">

                <!-- SAVE -->
                <button type="submit"
                        class="bg-gradient-to-r from-green-500 to-blue-500
                               hover:scale-105 transition
                               text-white px-6 py-3 rounded-xl shadow-lg font-semibold">

                    💾 Save Category

                </button>

                <!-- CANCEL -->
                <a href="{{ route('categories.index') }}"
                   class="bg-gray-300 hover:bg-gray-400
                          text-gray-800 px-6 py-3 rounded-xl shadow font-semibold">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

@endsection
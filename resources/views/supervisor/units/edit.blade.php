@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            ✏️ Edit Unit
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Update inventory unit information.
        </p>

    </div>

    @if ($errors->any())

        <div class="bg-red-500 text-white p-4 rounded-xl mb-4 shadow text-lg">

            <ul class="list-disc list-inside">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

        <form action="{{ route('units.update', $unit->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="mb-6">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Unit Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name', $unit->name) }}"
                       class="w-full border rounded-xl p-4 focus:ring-2 focus:ring-blue-400"
                       required>

            </div>

            <div class="flex gap-3">

                <button type="submit"
                        class="bg-gradient-to-r from-yellow-500 to-orange-500
                               hover:scale-105 transition
                               text-white px-6 py-3 rounded-xl shadow-lg font-semibold">

                    💾 Update Unit

                </button>

                <a href="{{ route('units.index') }}"
                   class="bg-gray-300 hover:bg-gray-400
                          text-gray-800 px-6 py-3 rounded-xl shadow font-semibold">

                    Cancel

                </a>

            </div>

        </form>

</div>

@endsection
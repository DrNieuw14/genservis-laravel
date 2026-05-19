@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-6">

        <h2 class="text-4xl font-bold text-white flex items-center gap-3">
            ➕ Add Unit
        </h2>

        <p class="text-white/80 mt-2">
            Create inventory units for GenServis.
        </p>

    </div>

    @if ($errors->any())

        <div class="bg-red-500 text-white p-4 rounded-xl mb-4 shadow">

            <ul class="list-disc list-inside">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="bg-white rounded-2xl shadow-2xl p-8">

        <form action="{{ route('units.store') }}" method="POST">

            @csrf

            <div class="mb-6">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Unit Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Example: Piece"
                       class="w-full border rounded-xl p-4 focus:ring-2 focus:ring-blue-400"
                       required>

            </div>

            <div class="flex gap-3">

                <button type="submit"
                        class="bg-gradient-to-r from-green-500 to-blue-500
                               hover:scale-105 transition
                               text-white px-6 py-3 rounded-xl shadow-lg font-semibold">

                    💾 Save Unit

                </button>

                <a href="{{ route('units.index') }}"
                   class="bg-gray-300 hover:bg-gray-400
                          text-gray-800 px-6 py-3 rounded-xl shadow font-semibold">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

@endsection
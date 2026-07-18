@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            📥 Import Inventory
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Upload Excel or CSV inventory records into GenServis.
        </p>

    </div>

    <!-- SUCCESS -->
    @if(session('success'))

        <div class="bg-green-500 text-white p-4 rounded-xl mb-6 text-lg">

            {{ session('success') }}

        </div>

    @endif

    <!-- ERRORS -->
    @if ($errors->any())

        <div class="bg-red-500 text-white p-4 rounded-xl mb-6 text-lg">

            <ul>

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

        <form method="POST"
              action="{{ route('materials.import.store') }}"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-6">

                <label class="block mb-2 font-semibold text-gray-700">

                    Select Excel File

                </label>

                <input
                    type="file"
                    name="file"
                    required
                    class="w-full border border-gray-300 rounded-xl p-3">

            </div>

            <button
                type="submit"
                class="bg-gradient-to-r from-green-500 to-blue-600
                       text-white px-6 py-3 rounded-xl shadow-lg">

                📤 Upload File

            </button>

        </form>

</div>

@endsection
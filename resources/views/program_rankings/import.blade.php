@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📥 Upload Raw Data of Examinees
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                The consolidated "LIST OF EXAMINEES" master sheet — Program and Address included.
            </p>
        </div>

        <x-back-button :href="route('program-rankings.index')" />
    </div>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg mb-6 text-sm">
        Reads the first sheet of the workbook only — the per-program tabs in the source file are
        just this same list filtered and sorted, so they don't need uploading separately.
        Re-uploading for the same admission year replaces its rankings entirely.
    </div>

    <form method="POST" action="{{ route('program-rankings.import.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Admission Year</label>
            <select name="admission_year_id" class="w-full border border-gray-300 rounded-xl p-3" required>
                <option value="">-- Select --</option>
                @foreach($years as $year)
                    <option value="{{ $year->id }}">{{ $year->label }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Select Excel File</label>
            <input type="file" name="file" required class="w-full border border-gray-300 rounded-xl p-3">
        </div>

        <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-6 py-3 rounded-xl shadow-lg">
            📤 Upload File
        </button>

    </form>

</div>

@endsection

@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📥 Upload Reapplication Responses
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                The Google Forms "Admission Reapplication Form" export.
            </p>
        </div>

        <x-back-button :href="route('reapplications.index')" />
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
        This form is open to every CvSU campus, not just Carmona — respondents from other campuses will
        correctly show "Not in Roster" since our roster only covers Carmona. Every submission is kept as its
        own record, even repeats — same Control Number appearing more than once is flagged as a possible
        duplicate submission for manual review, not merged automatically.
        Re-uploading for the same admission year replaces the whole set.
    </div>

    <form method="POST" action="{{ route('reapplications.import.store') }}" enctype="multipart/form-data">
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

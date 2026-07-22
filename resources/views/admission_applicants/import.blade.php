@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📥 Upload Applicant Roster
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $year->label }}
            </p>
        </div>

        <x-back-button :href="route('admission-applicants.index', $year->id)" />
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
        Expected columns: Control Number, Given Name, Middle Name, Family Name, Suffix, Date of Birth, Sex,
        House no., Street, Barangay, Municipality, Municipality Income Class, Province, Zip Code, Campus, Program, Email, Contact Number.
        Re-uploading a file for this same year will update existing applicants that share a Control Number, rather than duplicate them.
        Rows with a missing or clearly invalid Date of Birth are skipped automatically and listed after upload.
    </div>

    <form method="POST" action="{{ route('admission-applicants.import.store', $year->id) }}" enctype="multipart/form-data">
        @csrf

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

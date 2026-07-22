@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📥 Upload Exam Results
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $session->label }} — {{ $session->year->label }}
            </p>
        </div>

        <x-back-button :href="route('exam-sessions.show', $session->id)" />
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
        Expects the standard "Master List of Examinees" format — title rows, then a header row with
        Name of Examinee, Code, [T], Grade, PR, Remarks. Each row's Code is matched against that
        applicant's Control Number in the {{ $session->year->label }} roster automatically.
        Re-uploading into this session replaces its results entirely rather than adding to them.
    </div>

    <form method="POST" action="{{ route('exam-sessions.import.store', $session->id) }}" enctype="multipart/form-data">
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

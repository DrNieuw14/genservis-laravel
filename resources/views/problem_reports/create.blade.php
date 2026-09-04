@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                Report a Problem
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Busted light, broken door knob, or any other campus issue — Physical Plant and Services will review it.
            </p>
        </div>

        <a href="{{ route('problem-reports.my') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            📜 My Reports
        </a>
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

    <form method="POST" action="{{ route('problem-reports.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mt-2">
            <label class="block mb-2 font-semibold">
                Where is the problem?
            </label>

            <input
                type="text"
                name="location"
                value="{{ old('location') }}"
                placeholder="e.g. Room 204, Main Building"
                class="w-full border rounded-lg p-4"
                required>
        </div>

        <div class="mt-6">
            <label class="block mb-2 font-semibold">
                What's the problem?
            </label>

            <textarea
                name="problem_description"
                rows="4"
                placeholder="e.g. Busted light near the whiteboard, door knob is loose and won't lock"
                class="w-full border rounded-lg p-4"
                required>{{ old('problem_description') }}</textarea>
        </div>

        <div class="mt-6">
            <label class="block mb-2 font-semibold">
                Photo (optional)
            </label>

            <input
                type="file"
                name="photo"
                accept="image/*"
                onchange="previewProblemPhoto(this)"
                class="w-full border rounded-lg p-3 bg-white">

            @error('photo')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror

            <div id="problem-photo-preview" class="mt-3"></div>
        </div>

        <div class="mt-8">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow">
                📤 Submit Report
            </button>
        </div>

    </form>

    <script>

        function previewProblemPhoto(input)
        {
            const container = document.getElementById('problem-photo-preview');

            container.innerHTML = '';

            if (!input.files || !input.files[0]) {
                return;
            }

            const img = document.createElement('img');

            img.src = URL.createObjectURL(input.files[0]);
            img.className = 'w-24 h-24 object-cover rounded-lg border';

            container.appendChild(img);
        }

    </script>

</div>

@endsection

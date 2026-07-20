@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧾 New Project Estimate
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Project overview — you'll add cost items after saving.
            </p>
        </div>

        <x-back-button :href="route('project-estimates.index')" />
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

    <form method="POST" action="{{ route('project-estimates.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-2 font-semibold">Project Name</label>
                <input type="text" name="project_name" value="{{ old('project_name') }}"
                    placeholder="e.g. Repair of Split-Type Air Conditioner (4th Floor Server Room)"
                    class="w-full border rounded-lg p-4" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Location (optional)</label>
                <input type="text" name="location" value="{{ old('location') }}"
                    placeholder="e.g. 4th Floor Main Building CvSU Carmona"
                    class="w-full border rounded-lg p-4">
            </div>

        </div>

        <div class="mt-6">
            <label class="block mb-2 font-semibold">Scope of Work (optional)</label>
            <textarea name="scope_of_work" rows="3" class="w-full border rounded-lg p-4">{{ old('scope_of_work') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

            <div>
                <label class="block mb-2 font-semibold">Duration (optional)</label>
                <input type="text" name="duration" value="{{ old('duration') }}" placeholder="e.g. 1 day"
                    class="w-full border rounded-lg p-4">
            </div>

            <div>
                <label class="block mb-2 font-semibold">Related Job Request (optional)</label>
                <select name="job_request_id" class="w-full border rounded-lg p-4">
                    <option value="">— None —</option>
                    @foreach($jobRequests as $jr)
                        <option value="{{ $jr->id }}" {{ (int) old('job_request_id') === $jr->id ? 'selected' : '' }}>
                            {{ $jr->reference_no }} — {{ $jr->nature_of_request }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="mt-6">
            <label class="block mb-2 font-semibold">Assumptions (optional)</label>
            <textarea name="assumptions" rows="2" class="w-full border rounded-lg p-4">{{ old('assumptions') }}</textarea>
        </div>

        <div class="mt-6">
            <label class="block mb-2 font-semibold">Exclusions (optional)</label>
            <textarea name="exclusions" rows="2" class="w-full border rounded-lg p-4">{{ old('exclusions') }}</textarea>
        </div>

        <div class="mt-8">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow">
                💾 Save & Add Items
            </button>
        </div>

    </form>

</div>

@endsection

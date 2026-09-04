@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ✏️ Edit Job Request
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $jobRequest->reference_no }}
            </p>
        </div>

        <x-back-button :href="route('job-requests.show', $jobRequest->id)" />
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

    <form method="POST" action="{{ route('job-requests.update', $jobRequest->id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- OFFICE/UNIT/PROJECT -->
            <div>
                <label class="block mb-2 font-semibold">
                    Office / Unit / Project
                </label>

                <input
                    type="text"
                    name="office_unit_project"
                    value="{{ old('office_unit_project', $jobRequest->office_unit_project) }}"
                    class="w-full border rounded-lg p-4"
                    required>
            </div>

            <!-- DEPARTMENT -->
            <div>
                <label class="block mb-2 font-semibold">
                    Department (optional)
                </label>

                <select name="department_id" class="w-full border rounded-lg p-4">
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ (int) old('department_id', $jobRequest->department_id) === $department->id ? 'selected' : '' }}>
                            {{ $department->department_name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <!-- NATURE OF REQUEST -->
        <div class="mt-6">
            <label class="block mb-2 font-semibold">
                Nature of Request
            </label>

            <input
                type="text"
                name="nature_of_request"
                value="{{ old('nature_of_request', $jobRequest->nature_of_request) }}"
                class="w-full border rounded-lg p-4"
                required>
        </div>

        <!-- WORK SUMMARY -->
        <div class="mt-6">
            <label class="block mb-2 font-semibold">
                Work Summary
            </label>

            <textarea
                name="work_summary"
                rows="4"
                class="w-full border rounded-lg p-4"
                required>{{ old('work_summary', $jobRequest->work_summary) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

            <!-- WORK CATEGORY -->
            <div>
                <label class="block mb-2 font-semibold">
                    Work Category (optional)
                </label>

                <input
                    type="text"
                    name="work_category"
                    value="{{ old('work_category', $jobRequest->work_category) }}"
                    placeholder="e.g. Electrical, Plumbing, Cleaning"
                    class="w-full border rounded-lg p-4">
            </div>

            <!-- TARGET DATE -->
            <div>
                <label class="block mb-2 font-semibold">
                    Target Date (optional)
                </label>

                <input
                    type="date"
                    name="target_date"
                    value="{{ old('target_date', $jobRequest->target_date?->format('Y-m-d')) }}"
                    class="w-full border rounded-lg p-4">
            </div>

        </div>

        <!-- REMARKS -->
        <div class="mt-6">
            <label class="block mb-2 font-semibold">
                Remarks (optional)
            </label>

            <textarea
                name="remarks"
                rows="3"
                placeholder="Instructions for the assigned crew..."
                class="w-full border rounded-lg p-4">{{ old('remarks', $jobRequest->remarks) }}</textarea>
        </div>

        <p class="text-sm text-gray-500 mt-6">
            Category (Physical Plant & Services vs. Utility Personnel) can't be changed here, since it decides who already approved this request.
        </p>

        <div class="mt-8">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow">
                💾 Save Changes
            </button>
        </div>

    </form>

</div>

@endsection

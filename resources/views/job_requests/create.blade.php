@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🛠️ Job Request
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Request repair/rehabilitation work or utility help (moving, cleaning, etc.).
            </p>
        </div>

        <a href="{{ route('job-requests.history') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            📜 My Job Requests
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('job-requests.store') }}">
        @csrf

        <!-- CATEGORY -->
        <div class="mb-6">

            <label class="block mb-3 font-semibold text-lg">
                What kind of job request is this?
            </label>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <label class="border-2 rounded-xl p-5 cursor-pointer transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                    <input type="radio" name="category" value="physical_plant" class="mr-2" {{ old('category') === 'physical_plant' ? 'checked' : '' }} required>
                    <span class="font-semibold text-lg">🏗️ Physical Plant & Services</span>
                    <p class="text-gray-500 mt-1">
                        Rehabilitation, repair, or fixing of school infrastructure
                        (electrical, plumbing, structural, etc.). Approved by
                        Physical Plant and Services.
                    </p>
                </label>

                <label class="border-2 rounded-xl p-5 cursor-pointer transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                    <input type="radio" name="category" value="utility" class="mr-2" {{ old('category') === 'utility' ? 'checked' : '' }} required>
                    <span class="font-semibold text-lg">🧹 Utility Personnel</span>
                    <p class="text-gray-500 mt-1">
                        General help — moving chairs/tables, cleaning an area, etc.
                        Approved by the General Services Officer.
                    </p>
                </label>

            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- OFFICE/UNIT/PROJECT -->
            <div>
                <label class="block mb-2 font-semibold">
                    Office / Unit / Project
                </label>

                <input
                    type="text"
                    name="office_unit_project"
                    value="{{ old('office_unit_project') }}"
                    placeholder="e.g. Main Building - Ground Floor"
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
                        <option value="{{ $department->id }}" {{ (int) old('department_id') === $department->id ? 'selected' : '' }}>
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
                value="{{ old('nature_of_request') }}"
                placeholder="Short title, e.g. Replace broken ceiling fan"
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
                placeholder="Describe the work needed in more detail."
                class="w-full border rounded-lg p-4"
                required>{{ old('work_summary') }}</textarea>
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
                    value="{{ old('work_category') }}"
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
                    value="{{ old('target_date') }}"
                    class="w-full border rounded-lg p-4">
            </div>

        </div>

        <div class="mt-8">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow">
                📤 Submit Job Request
            </button>
        </div>

    </form>

</div>

@endsection

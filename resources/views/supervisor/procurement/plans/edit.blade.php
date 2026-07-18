@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            ✏️ Edit Annual PPMP
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            {{ $plan->plan_number }}
        </p>

    </div>

    <form method="POST"
    action="{{ route('procurement.plans.update', $plan->id) }}">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- PPMP Number -->

            <div class="md:col-span-2">

                <label class="font-semibold text-lg">
                    PPMP Number
                </label>

                <input
                    type="text"
                    class="w-full border border-gray-300 rounded-lg p-3 mt-2 text-lg bg-gray-100"
                    value="{{ $plan->plan_number }}"
                    readonly>

            </div>

            <div>

                <label class="font-semibold text-lg">
                    Planning Year
                </label>

                <input
                    type="number"
                    name="year"
                    value="{{ old('year', $plan->year) }}"
                    class="w-full border border-gray-300 rounded-lg p-3 mt-2 text-lg focus:ring-2 focus:ring-blue-400 outline-none">

            </div>

            <div>

                <label class="font-semibold text-lg">
                    Department
                </label>

                <select
                    name="department_id"
                    class="w-full border border-gray-300 rounded-lg p-3 mt-2 text-lg focus:ring-2 focus:ring-blue-400 outline-none">

                    <option value="">Select Department</option>

                    @foreach($departments as $department)

                        <option
                            value="{{ $department->id }}"
                            @selected(old('department_id', $plan->department_id) == $department->id)>

                            {{ $department->department_name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div>

                <label class="font-semibold text-lg">
                    Allocated Budget
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="allocated_budget"
                    value="{{ old('allocated_budget', $plan->allocated_budget) }}"
                    class="w-full border border-gray-300 rounded-lg p-3 mt-2 text-lg focus:ring-2 focus:ring-blue-400 outline-none">

            </div>

            <div>

                <label class="font-semibold text-lg">
                    Status
                </label>

                <input
                    class="w-full border border-gray-300 rounded-lg p-3 mt-2 text-lg bg-gray-100"
                    value="{{ $plan->status }}"
                    readonly>

            </div>

            <div class="md:col-span-2">

                <label class="font-semibold text-lg">
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    rows="4"
                    class="w-full border border-gray-300 rounded-lg p-3 mt-2 text-lg focus:ring-2 focus:ring-blue-400 outline-none">{{ old('remarks', $plan->remarks) }}</textarea>

            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3">

            <a
                href="{{ route('procurement.plans.show', $plan->id) }}"
                class="px-6 py-3 border border-gray-300 rounded-lg text-lg font-semibold hover:bg-gray-50">

                Cancel

            </a>

            <button
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-lg font-semibold shadow">

                💾 Update PPMP

            </button>

        </div>

    </form>

</div>

@endsection

<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Annual PPMP
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="max-w-4xl mx-auto">

            <div class="bg-white rounded-lg shadow p-6">

                <form method="POST"
                action="{{ route('procurement.plans.update', $plan->id) }}">

                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-6">

                        <!-- PPMP Number -->

                        <div class="col-span-2">

                            <label class="font-semibold">
                                PPMP Number
                            </label>

                            <input
                                type="text"
                                class="w-full border rounded mt-2 bg-gray-100"
                                value="{{ $plan->plan_number }}"
                                readonly>

                        </div>

                    <div>

                    <label class="font-semibold">

                        Planning Year

                    </label>

                    <input
                        type="number"
                        name="year"
                        value="{{ old('year', $plan->year) }}"
                        class="w-full border rounded mt-2">

                    </div>

                    <div>

                        <label class="font-semibold">

                         Department

                        </label>

                        <select
                        name="department_id"
                        class="w-full border rounded mt-2">

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

                        <label class="font-semibold">

                        Allocated Budget

                        </label>

                        <input
                        type="number"
                        step="0.01"
                        name="allocated_budget"
                        value="{{ old('allocated_budget', $plan->allocated_budget) }}"
                        class="w-full border rounded mt-2">

                    </div>

                    <div>

                        <label class="font-semibold">

                            Status

                        </label>

                        <input
                        class="w-full border rounded mt-2 bg-gray-100"
                        value="{{ $plan->status }}"
                        readonly>

                    </div>

                    <div class="col-span-2">

                        <label class="font-semibold">

                            Remarks

                        </label>

                        <textarea
                        name="remarks"
                        rows="4"
                        class="w-full border rounded mt-2">{{ old('remarks', $plan->remarks) }}</textarea>

                    </div>

                    </div>

                    <div class="mt-8 flex justify-end gap-3">

                        <a
                            href="{{ route('procurement.plans.show', $plan->id) }}"
                            class="px-6 py-2 border rounded">

                            Cancel

                        </a>

                        <button
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">

                            Update PPMP

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>

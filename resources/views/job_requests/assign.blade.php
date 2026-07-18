@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex justify-between items-start mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                👷 Assign Personnel
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $jobRequest->reference_no }} — {{ $jobRequest->nature_of_request }}
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

    <form method="POST" action="{{ route('job-requests.assign.store', $jobRequest->id) }}">
        @csrf

        <label class="block mb-2 font-semibold">
            Utility & Maintenance Staff
        </label>

        <p class="text-sm text-gray-500 mb-3">
            Select everyone who will work on this job.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 border rounded-lg p-4 bg-gray-50">

            @php
                $selected = old('personnel_ids', $jobRequest->assignedPersonnel->pluck('id')->all());
                $selected = collect($selected)->map(fn ($id) => (int) $id);
            @endphp

            @forelse($staff as $person)

                <label class="flex items-center gap-2 text-sm text-gray-700 bg-white border rounded-lg px-3 py-2">

                    <input
                        type="checkbox"
                        name="personnel_ids[]"
                        value="{{ $person->id }}"
                        {{ $selected->contains($person->id) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">

                    <span>
                        {{ $person->fullname }}
                        <span class="text-gray-500">
                            ({{ $person->positionRecord->position_name ?? '-' }})
                        </span>
                    </span>

                </label>

            @empty

                <p class="text-gray-500 col-span-2">
                    No utility or maintenance staff on record yet. Add them via Employee Onboarding first.
                </p>

            @endforelse

        </div>

        <div class="mt-8">
            <button type="submit"
                class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-8 py-3 rounded-lg shadow">
                💾 Save Assignment
            </button>
        </div>

    </form>

</div>

@endsection

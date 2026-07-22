@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ✏️ Edit Consultation — {{ $consultation->case_no }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $consultation->patient_name }}
            </p>
        </div>

        <x-back-button :href="route('health-consultations.show', $consultation->id)" />
    </div>

    @include('health_consultations._form')

</div>

@endsection

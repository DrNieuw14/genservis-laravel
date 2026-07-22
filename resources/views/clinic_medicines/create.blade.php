@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                💊 Add Medicine
            </h2>
        </div>

        <x-back-button :href="route('clinic-medicines.index')" />
    </div>

    @include('clinic_medicines._form')

</div>

@endsection

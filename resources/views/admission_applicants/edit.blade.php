@extends('layouts.app')

@section('content')

@php
    $old = fn ($field) => old($field, $applicant->{$field});
    $dobValue = old('date_of_birth', $applicant->date_of_birth?->format('Y-m-d'));
@endphp

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ✏️ Edit Applicant
            </h2>
            <p class="text-gray-500 mt-1 text-lg">{{ $year->label }}</p>
        </div>

        <x-back-button :href="route('admission-applicants.show', [$year->id, $applicant->id])" />
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

    <form method="POST" action="{{ route('admission-applicants.update', [$year->id, $applicant->id]) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label class="block mb-2 font-semibold text-sm">Control Number</label>
                <input type="text" name="control_number" value="{{ $old('control_number') }}" class="w-full border rounded-lg p-3" required>
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Given Name</label>
                <input type="text" name="given_name" value="{{ $old('given_name') }}" class="w-full border rounded-lg p-3" required>
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Middle Name</label>
                <input type="text" name="middle_name" value="{{ $old('middle_name') }}" class="w-full border rounded-lg p-3">
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Family Name</label>
                <input type="text" name="family_name" value="{{ $old('family_name') }}" class="w-full border rounded-lg p-3" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label class="block mb-2 font-semibold text-sm">Suffix</label>
                <input type="text" name="suffix" value="{{ $old('suffix') }}" class="w-full border rounded-lg p-3">
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ $dobValue }}" class="w-full border rounded-lg p-3" required>
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Sex</label>
                <select name="sex" class="w-full border rounded-lg p-3">
                    <option value="">-- Select --</option>
                    <option value="Male" @selected($old('sex') === 'Male')>Male</option>
                    <option value="Female" @selected($old('sex') === 'Female')>Female</option>
                </select>
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Campus</label>
                <input type="text" name="campus" value="{{ $old('campus') }}" class="w-full border rounded-lg p-3">
            </div>
        </div>

        <div class="mb-6">
            <label class="block mb-2 font-semibold text-sm">Program</label>
            <input type="text" name="program" value="{{ $old('program') }}" class="w-full border rounded-lg p-3">
        </div>

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Address</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label class="block mb-2 font-semibold text-sm">House No.</label>
                <input type="text" name="house_no" value="{{ $old('house_no') }}" class="w-full border rounded-lg p-3">
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Street</label>
                <input type="text" name="street" value="{{ $old('street') }}" class="w-full border rounded-lg p-3">
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Barangay</label>
                <input type="text" name="barangay" value="{{ $old('barangay') }}" class="w-full border rounded-lg p-3">
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Municipality</label>
                <input type="text" name="municipality" value="{{ $old('municipality') }}" class="w-full border rounded-lg p-3">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block mb-2 font-semibold text-sm">Province</label>
                <input type="text" name="province" value="{{ $old('province') }}" class="w-full border rounded-lg p-3">
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Zip Code</label>
                <input type="text" name="zip_code" value="{{ $old('zip_code') }}" class="w-full border rounded-lg p-3">
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Municipality Income Class</label>
                <input type="text" name="municipality_income_class" value="{{ $old('municipality_income_class') }}" class="w-full border rounded-lg p-3">
            </div>
        </div>

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block mb-2 font-semibold text-sm">Email</label>
                <input type="email" name="email" value="{{ $old('email') }}" class="w-full border rounded-lg p-3">
            </div>
            <div>
                <label class="block mb-2 font-semibold text-sm">Contact Number</label>
                <input type="text" name="contact_number" value="{{ $old('contact_number') }}" class="w-full border rounded-lg p-3">
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow">
                💾 Save
            </button>
            <a href="{{ route('admission-applicants.show', [$year->id, $applicant->id]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-8 py-3 rounded-lg">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection

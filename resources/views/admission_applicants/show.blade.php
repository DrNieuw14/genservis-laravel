@extends('layouts.app')

@section('content')

@php
    $field = fn ($label, $value) => '<p class="text-sm text-gray-500">' . $label . '</p><p class="font-semibold text-lg mt-1">' . ($value !== null && $value !== '' ? e($value) : '-') . '</p>';
@endphp

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🎓 {{ $applicant->fullName() }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Control No. {{ $applicant->control_number }} — {{ $year->label }}
            </p>
        </div>

        <div class="flex gap-2">
            <x-back-button :href="route('admission-applicants.index', $year->id)" />

            <a href="{{ route('admission-applicants.edit', [$year->id, $applicant->id]) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                ✏️ Edit
            </a>

            <form method="POST" action="{{ route('admission-applicants.destroy', [$year->id, $applicant->id]) }}"
                  onsubmit="return confirm('Remove this applicant record? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    🗑 Delete
                </button>
            </form>
        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="border rounded-lg p-6 bg-gray-50 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>{!! $field('Given Name', $applicant->given_name) !!}</div>
            <div>{!! $field('Middle Name', $applicant->middle_name) !!}</div>
            <div>{!! $field('Family Name', $applicant->family_name) !!}</div>
            <div>{!! $field('Suffix', $applicant->suffix) !!}</div>
            <div>{!! $field('Date of Birth', $applicant->date_of_birth->format('M d, Y')) !!}</div>
            <div>{!! $field('Sex', $applicant->sex) !!}</div>
            <div>{!! $field('Campus', $applicant->campus) !!}</div>
            <div>{!! $field('Program', $applicant->program) !!}</div>
        </div>
    </div>

    <div class="border rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Address</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>{!! $field('House No.', $applicant->house_no) !!}</div>
            <div>{!! $field('Street', $applicant->street) !!}</div>
            <div>{!! $field('Barangay', $applicant->barangay) !!}</div>
            <div>{!! $field('Municipality', $applicant->municipality) !!}</div>
            <div>{!! $field('Province', $applicant->province) !!}</div>
            <div>{!! $field('Zip Code', $applicant->zip_code) !!}</div>
            <div>{!! $field('Municipality Income Class', $applicant->municipality_income_class) !!}</div>
        </div>
    </div>

    <div class="border rounded-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Contact</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>{!! $field('Email', $applicant->email) !!}</div>
            <div>{!! $field('Contact Number', $applicant->contact_number) !!}</div>
        </div>
    </div>

</div>

@endsection

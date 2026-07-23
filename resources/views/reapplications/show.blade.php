@extends('layouts.app')

@section('content')

@php
    $field = fn ($label, $value) => '<p class="text-sm text-gray-500">' . $label . '</p><p class="font-semibold text-lg mt-1">' . ($value !== null && $value !== '' ? e($value) : '-') . '</p>';
@endphp

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🔄 {{ $reapplication->fullName() }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $reapplication->year->label }}
                @if($reapplication->is_duplicate)
                    <span class="text-yellow-600 font-semibold ml-2">⚠ Possible duplicate submission</span>
                @endif
            </p>
        </div>

        <x-back-button :href="route('reapplications.index', ['admission_year_id' => $reapplication->admission_year_id])" />

    </div>

    <div class="border rounded-lg p-6 bg-gray-50 mb-6">

        <h3 class="text-xl font-semibold text-gray-800 mb-4">Applicant Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>{!! $field('Control Number', $reapplication->control_number) !!}</div>
            <div>{!! $field('Email', $reapplication->email) !!}</div>
            <div>{!! $field('Campus', $reapplication->campus) !!}</div>
            <div>{!! $field('Address', $reapplication->address) !!}</div>
        </div>

    </div>

    <div class="border rounded-lg p-6 mb-6">

        <h3 class="text-xl font-semibold text-gray-800 mb-4">Exam Performance (Original Program)</h3>

        @if($reapplication->examRank !== null)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>{!! $field('Score', $reapplication->examScore) !!}</div>
                <div>{!! $field('Grade', $reapplication->examGrade) !!}</div>
                <div>
                    {!! $field('Rank', $reapplication->examRank . ' of ' . $reapplication->examTotal) !!}
                    <a href="{{ route('program-rankings.show', [$reapplication->admission_year_id, $reapplication->examProgramCode]) }}"
                       class="text-blue-600 hover:underline text-sm">
                        View {{ $reapplication->examProgramCode }} rankings →
                    </a>
                </div>
            </div>
        @else
            <p class="text-gray-500 text-sm">
                No exam record found for this person — either they didn't take the exam, or their
                Control Number doesn't match a roster record.
            </p>
        @endif

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        <div class="border rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Original Application</h3>
            <div class="space-y-3">
                {!! $field('Program Applied For', $reapplication->program_applied_for) !!}
                {!! $field('Track', $reapplication->track) !!}
                {!! $field('Strand', $reapplication->strand) !!}
            </div>
        </div>

        <div class="border rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Reapplication Choices</h3>
            <div class="space-y-3">
                {!! $field('First Choice', $reapplication->first_choice) !!}
                {!! $field('Second Choice', $reapplication->second_choice) !!}
            </div>
        </div>

    </div>

    <div class="border rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Roster Cross-Check</h3>

        @if($reapplication->match_status === 'matched')
            <p class="text-green-700 font-semibold mb-2">{{ $reapplication->matchStatusLabel() }}</p>
            <a href="{{ route('admission-applicants.show', [$reapplication->admission_year_id, $reapplication->admission_applicant_id]) }}"
               class="text-blue-600 hover:underline">
                View roster record →
            </a>
        @elseif($reapplication->match_status === 'name_mismatch')
            <p class="text-yellow-700 font-semibold mb-2">{{ $reapplication->matchStatusLabel() }}</p>
            <p class="text-sm text-gray-500 mb-2">Roster name on file: {{ $reapplication->applicant->fullName() ?? '-' }}</p>
            <a href="{{ route('admission-applicants.show', [$reapplication->admission_year_id, $reapplication->admission_applicant_id]) }}"
               class="text-blue-600 hover:underline">
                View roster record →
            </a>
        @else
            <p class="text-red-700 font-semibold">{{ $reapplication->matchStatusLabel() }}</p>
            <p class="text-sm text-gray-500 mt-1">
                No Carmona roster record matched this Control Number — expected if this respondent applied
                through a different CvSU campus, otherwise worth a manual look.
            </p>
        @endif
    </div>

</div>

@endsection

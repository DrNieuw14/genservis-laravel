@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📝 {{ $session->label }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $session->year->label }}
                @if($session->exam_date)
                    — {{ $session->exam_date->format('M d, Y') }}
                @endif
            </p>
        </div>

        <div class="flex gap-2">
            <x-back-button :href="route('exam-sessions.index')" />

            <a href="{{ route('exam-sessions.import', $session->id) }}"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow whitespace-nowrap">
                📥 Upload Results
            </a>
        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-gray-50 border rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $summary['total'] }}</p>
            <p class="text-sm text-gray-500">Total Results</p>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-green-700">{{ $summary['matched'] }}</p>
            <p class="text-sm text-green-600">✅ Matched</p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-yellow-700">{{ $summary['name_mismatch'] }}</p>
            <p class="text-sm text-yellow-600">⚠ Name Mismatch</p>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-red-700">{{ $summary['not_found'] }}</p>
            <p class="text-sm text-red-600">❌ Not in Roster</p>
        </div>

    </div>

    @if($summary['not_found'] > 0 || $summary['name_mismatch'] > 0)
        <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 p-4 mb-6 rounded-lg text-sm">
            ⚠ {{ $summary['not_found'] + $summary['name_mismatch'] }} row(s) below need a manual look — either the Code didn't match anyone in the applicant roster, or it matched a different name than the one on the results sheet.
        </div>
    @endif

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Code</th>
                    <th class="p-3 text-left">Name on Sheet</th>
                    <th class="p-3 text-center">Score</th>
                    <th class="p-3 text-center">Grade</th>
                    <th class="p-3 text-center">PR</th>
                    <th class="p-3 text-left">Remarks</th>
                    <th class="p-3 text-center">Roster Match</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($session->results as $result)

                    <tr class="hover:bg-gray-50 {{ $result->match_status !== 'matched' ? 'bg-red-50/40' : '' }}">

                        <td class="p-3">{{ $result->code ?? '-' }}</td>

                        <td class="p-3 font-semibold">{{ $result->examinee_name }}</td>

                        <td class="p-3 text-center">{{ $result->raw_score ?? '-' }}</td>

                        <td class="p-3 text-center">{{ $result->grade ?? '-' }}</td>

                        <td class="p-3 text-center">{{ $result->percentile_rank ?? '-' }}</td>

                        <td class="p-3">{{ $result->remarks ?? '-' }}</td>

                        <td class="p-3 text-center">
                            @if($result->match_status === 'matched')
                                <a href="{{ route('admission-applicants.show', [$session->admission_year_id, $result->admission_applicant_id]) }}"
                                   class="text-green-700 hover:underline">
                                    {{ $result->matchStatusLabel() }}
                                </a>
                            @elseif($result->match_status === 'name_mismatch')
                                <a href="{{ route('admission-applicants.show', [$session->admission_year_id, $result->admission_applicant_id]) }}"
                                   class="text-yellow-700 hover:underline" title="Roster name: {{ $result->applicant->fullName() ?? '' }}">
                                    {{ $result->matchStatusLabel() }}
                                </a>
                            @else
                                <span class="text-red-700">{{ $result->matchStatusLabel() }}</span>
                            @endif
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No results uploaded yet for this session.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

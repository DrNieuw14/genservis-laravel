@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📋 All Exam Takers
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $year->label }} — {{ $rankings->total() }} examinee(s) across every program
            </p>
        </div>

        <x-back-button :href="route('program-rankings.index', ['admission_year_id' => $year->id])" />

    </div>

    <form method="GET" class="mb-6 flex flex-col md:flex-row gap-3">

        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or code"
            class="w-full border rounded-lg p-3">

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow whitespace-nowrap">
            🔍 Search
        </button>

        @if($search)
            <a href="{{ route('program-rankings.all', $year->id) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg shadow text-center">
                Clear
            </a>
        @endif

    </form>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Code</th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Program</th>
                    <th class="p-3 text-left">Address</th>
                    <th class="p-3 text-center">Score</th>
                    <th class="p-3 text-center">Grade</th>
                    <th class="p-3 text-center">PR</th>
                    <th class="p-3 text-center">Roster Match</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($rankings as $r)

                    <tr class="hover:bg-gray-50 {{ $r->match_status !== 'matched' ? 'bg-red-50/40' : '' }}">

                        <td class="p-3">{{ $r->code ?? '-' }}</td>

                        <td class="p-3 font-semibold">{{ $r->examinee_name }}</td>

                        <td class="p-3">
                            <a href="{{ route('program-rankings.show', [$year->id, $r->shortProgramCode()]) }}" class="text-blue-600 hover:underline">
                                {{ $r->shortProgramCode() }}
                            </a>
                        </td>

                        <td class="p-3">{{ $r->address ?? '-' }}</td>

                        <td class="p-3 text-center">{{ $r->raw_score ?? '-' }}</td>

                        <td class="p-3 text-center">{{ $r->grade ?? '-' }}</td>

                        <td class="p-3 text-center">{{ $r->percentile_rank ?? '-' }}</td>

                        <td class="p-3 text-center">
                            @if($r->match_status === 'matched')
                                <a href="{{ route('admission-applicants.show', [$year->id, $r->admission_applicant_id]) }}"
                                   class="text-green-700 hover:underline">
                                    {{ $r->matchStatusLabel() }}
                                </a>
                            @elseif($r->match_status === 'name_mismatch')
                                <a href="{{ route('admission-applicants.show', [$year->id, $r->admission_applicant_id]) }}"
                                   class="text-yellow-700 hover:underline" title="Roster name: {{ $r->applicant->fullName() ?? '' }}">
                                    {{ $r->matchStatusLabel() }}
                                </a>
                            @else
                                <span class="text-red-700">{{ $r->matchStatusLabel() }}</span>
                            @endif
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">
                            No examinees found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $rankings->links() }}
    </div>

</div>

@endsection

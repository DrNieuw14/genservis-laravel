@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🏆 {{ $programCode }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $programName }} — {{ $year->label }} — {{ $rankings->count() }} examinee(s), ranked by Grade
            </p>
        </div>

        <x-back-button :href="route('program-rankings.index', ['admission_year_id' => $year->id])" />

    </div>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">Rank</th>
                    <th class="p-3 text-left">Code</th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Address</th>
                    <th class="p-3 text-center">Score</th>
                    <th class="p-3 text-center">Grade</th>
                    <th class="p-3 text-center">PR</th>
                    <th class="p-3 text-center">Roster Match</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($rankings as $i => $r)

                    <tr class="hover:bg-gray-50 {{ $r->match_status !== 'matched' ? 'bg-red-50/40' : '' }}">

                        <td class="p-3 text-center font-semibold">{{ $i + 1 }}</td>

                        <td class="p-3">{{ $r->code ?? '-' }}</td>

                        <td class="p-3 font-semibold">{{ $r->examinee_name }}</td>

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
                            No examinees found for this program.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

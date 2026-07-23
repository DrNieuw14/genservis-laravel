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

        <div class="flex gap-2">
            <x-back-button :href="route('program-rankings.index', ['admission_year_id' => $year->id])" />

            @if($quota->isSet())
                <a href="{{ route('program-rankings.admitted-report.print', $year->id) }}?program={{ $programCode }}"
                   target="_blank"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    🖨 Print Admitted Report
                </a>
            @endif
        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- PROGRAM CAPACITY -->
    <div class="bg-gray-50 border rounded-lg p-5 mb-6">

        <form method="POST" action="{{ route('program-rankings.quota.update', [$year->id, $programCode]) }}"
              class="flex flex-wrap items-end gap-4">
            @csrf
            <input type="hidden" name="program_name" value="{{ $programName }}">

            <div>
                <label class="block mb-1 font-semibold text-sm">Sections</label>
                <input type="number" min="0" name="sections" value="{{ old('sections', $quota->sections) }}"
                    class="w-24 border rounded-lg p-2 text-center">
            </div>

            <span class="text-gray-400 pb-2">×</span>

            <div>
                <label class="block mb-1 font-semibold text-sm">Students per Section</label>
                <input type="number" min="0" name="students_per_section" value="{{ old('students_per_section', $quota->students_per_section) }}"
                    class="w-32 border rounded-lg p-2 text-center">
            </div>

            <span class="text-gray-400 pb-2">=</span>

            <div class="pb-2">
                <p class="text-xs text-gray-500">Quota</p>
                <p class="text-2xl font-bold text-green-700">{{ $quota->quota() ?: '-' }}</p>
            </div>

            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded-lg shadow">
                💾 Save Capacity
            </button>

            @if($quota->isSet())
                <span class="text-sm text-gray-500 pb-2">
                    {{ $rankings->where('admitted', true)->count() }} of {{ $rankings->count() }} admitted
                    @if($rankings->where('admitted', true)->count() > $quota->quota())
                        (includes ties at the cutoff score)
                    @endif
                </span>
            @else
                <span class="text-sm text-gray-500 pb-2">No capacity set yet — rankings shown below, no admit/not-admit line drawn.</span>
            @endif

        </form>

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
                    <th class="p-3 text-center">Status</th>
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
                            @if($r->admitted === true)
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">✅ Admitted</span>
                            @elseif($r->admitted === false)
                                <span class="bg-gray-100 text-gray-500 text-xs font-semibold px-2 py-1 rounded-full">Not Admitted</span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>

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
                        <td colspan="9" class="p-6 text-center text-gray-500">
                            No examinees found for this program.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

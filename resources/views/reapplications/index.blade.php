@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🔄 Reapplications
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Students reapplying to an alternate program after not making their original program's quota.
            </p>
        </div>

        <a href="{{ route('reapplications.import') }}"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow whitespace-nowrap">
            📥 Upload Responses
        </a>

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

    @if($years->count() > 1)
        <form method="GET" class="mb-4 flex items-center gap-3">
            <label class="font-semibold text-sm">Admission Year:</label>
            <select name="admission_year_id" onchange="this.form.submit()" class="border rounded-lg p-2">
                @foreach($years as $year)
                    <option value="{{ $year->id }}" @selected($selectedYear && $selectedYear->id === $year->id)>{{ $year->label }}</option>
                @endforeach
            </select>
        </form>
    @endif

    @if(!$selectedYear)

        <p class="text-gray-500 text-center py-10">No admission years yet — create one under Admission Years first.</p>

    @else

        <div class="flex flex-wrap gap-3 mb-4">
            <a href="{{ route('reapplications.index', ['admission_year_id' => $selectedYear->id]) }}"
               class="px-4 py-2 rounded-lg border {{ !$filter ? 'bg-blue-600 text-white border-blue-600' : 'hover:bg-gray-50' }}">
                All ({{ $reapplications->total() ?? 0 }})
            </a>
            <a href="{{ route('reapplications.index', ['admission_year_id' => $selectedYear->id, 'filter' => 'duplicates']) }}"
               class="px-4 py-2 rounded-lg border {{ $filter === 'duplicates' ? 'bg-yellow-600 text-white border-yellow-600' : 'hover:bg-gray-50' }}">
                ⚠ Possible Duplicates ({{ $duplicateCount }})
            </a>
            <a href="{{ route('reapplications.index', ['admission_year_id' => $selectedYear->id, 'filter' => 'unresolved']) }}"
               class="px-4 py-2 rounded-lg border {{ $filter === 'unresolved' ? 'bg-red-600 text-white border-red-600' : 'hover:bg-gray-50' }}">
                ❌ No Usable Control No. ({{ $unresolvedCount }})
            </a>
        </div>

        <form method="GET" class="mb-4 flex items-center gap-3">
            <input type="hidden" name="admission_year_id" value="{{ $selectedYear->id }}">
            @if($filter)<input type="hidden" name="filter" value="{{ $filter }}">@endif

            <label class="font-semibold text-sm whitespace-nowrap">Filter by Program (1st or 2nd Choice):</label>
            <select name="program" onchange="showSortLoading(); this.form.submit()" class="border rounded-lg p-2">
                <option value="">-- All Programs --</option>
                @foreach($programOptions as $choiceText => $code)
                    <option value="{{ $code }}" @selected($programCode === $code)>{{ $code }}</option>
                @endforeach
            </select>

            @if($programCode)
                <a href="{{ route('reapplications.index', ['admission_year_id' => $selectedYear->id, 'filter' => $filter]) }}"
                   class="text-sm text-blue-600 hover:underline">Clear program filter</a>
            @endif
        </form>

        @if($programCode)
            <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg mb-4 text-sm">
                Showing everyone who listed <strong>{{ $programCode }}</strong> as their 1st or 2nd choice,
                ranked by their actual exam Grade (highest first) — best candidates for an open seat appear at the top.
                @if($programQuotaStatus)
                    <span class="font-semibold">
                        🎯 {{ $programQuotaStatus['admitted'] }} of {{ $programQuotaStatus['quota'] }} slots filled —
                        {{ $programQuotaStatus['remaining'] }} remaining.
                    </span>
                @else
                    No capacity set yet for {{ $programCode }} — set it on that program's ranking page to see remaining slots here.
                @endif
            </div>
        @endif

        <form method="GET" class="mb-6 flex flex-col md:flex-row gap-3" onsubmit="showSortLoading()">
            <input type="hidden" name="admission_year_id" value="{{ $selectedYear->id }}">
            @if($filter)<input type="hidden" name="filter" value="{{ $filter }}">@endif
            @if($programCode)<input type="hidden" name="program" value="{{ $programCode }}">@endif

            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or control number"
                class="w-full border rounded-lg p-3">

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow whitespace-nowrap">
                🔍 Search
            </button>

            @if($search)
                <a href="{{ route('reapplications.index', ['admission_year_id' => $selectedYear->id, 'filter' => $filter, 'program' => $programCode]) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg shadow text-center">
                    Clear
                </a>
            @endif
        </form>

        <div class="overflow-x-auto border rounded-lg">

            @php
                $defaultDir = ['surname' => 'asc', 'grade' => 'desc', 'rank' => 'asc'];
                $activeColor = ['grade' => 'text-blue-700', 'rank' => 'text-purple-700'];
                $sortLink = function ($column, $label) use ($sort, $direction, $defaultDir, $activeColor) {
                    $isActive = $sort === $column;
                    $nextDirection = $isActive ? ($direction === 'asc' ? 'desc' : 'asc') : $defaultDir[$column];
                    $arrow = $isActive ? ($direction === 'asc' ? '▲' : '▼') : '';
                    $url = request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection, 'page' => 1]);
                    $color = $activeColor[$column] ?? 'text-blue-700';
                    $inactiveIcon = ' <span class="text-gray-300">⇅</span>';

                    return '<a href="' . $url . '" onclick="showSortLoading()" class="cursor-pointer underline decoration-dotted '
                        . ($isActive ? 'font-bold ' . $color : $color . ' opacity-70 hover:opacity-100') . '">'
                        . $label . ($arrow ? ' ' . $arrow : $inactiveIcon) . '</a>';
                };
            @endphp

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Control No.</th>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Campus</th>
                        <th class="p-3 text-left">Applied For</th>
                        <th class="p-3 text-left">Track</th>
                        <th class="p-3 text-left">1st Choice</th>
                        <th class="p-3 text-left">2nd Choice</th>
                        <th class="p-3 text-center">{!! $sortLink('grade', 'Score / Grade') !!}</th>
                        <th class="p-3 text-center">{!! $sortLink('rank', 'Rank') !!}</th>
                        <th class="p-3 text-center">Roster Match</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($reapplications as $r)

                        <tr class="hover:bg-gray-50 {{ $r->is_duplicate ? 'bg-yellow-50' : '' }}">

                            <td class="p-3">
                                {{ $r->control_number ?? '-' }}
                                @if($r->is_duplicate)
                                    <span class="text-yellow-600 text-xs font-semibold" title="Same Control Number submitted more than once">⚠ dup</span>
                                @endif
                            </td>

                            <td class="p-3 font-semibold">{{ $r->fullName() }}</td>

                            <td class="p-3">{{ $r->campus ?? '-' }}</td>

                            <td class="p-3">{{ $r->program_applied_for ?? '-' }}</td>

                            <td class="p-3">{{ $r->track ?? '-' }}</td>

                            <td class="p-3 {{ $programCode && \App\Models\Reapplication::programCodeForChoice($r->first_choice) === $programCode ? 'bg-blue-100 font-semibold' : '' }}">
                                {{ $r->first_choice ?? '-' }}
                            </td>

                            <td class="p-3 {{ $programCode && \App\Models\Reapplication::programCodeForChoice($r->second_choice) === $programCode ? 'bg-blue-100 font-semibold' : '' }}">
                                {{ $r->second_choice ?? '-' }}
                            </td>

                            <td class="p-3 text-center">
                                @if($r->examGrade !== null)
                                    {{ $r->examScore ?? '-' }} / {{ $r->examGrade }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="p-3 text-center">
                                @if($r->examRank !== null)
                                    {{ $r->examRank }} of {{ $r->examTotal }}
                                    <div class="text-xs text-gray-400">{{ $r->examProgramCode }}</div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="p-3 text-center">
                                @if($r->match_status === 'matched')
                                    <span class="text-green-700">{{ $r->matchStatusLabel() }}</span>
                                @elseif($r->match_status === 'name_mismatch')
                                    <span class="text-yellow-700">{{ $r->matchStatusLabel() }}</span>
                                @else
                                    <span class="text-red-700">{{ $r->matchStatusLabel() }}</span>
                                @endif
                            </td>

                            <td class="p-3 text-center">
                                <a href="{{ route('reapplications.show', $r->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                    View
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="11" class="p-6 text-center text-gray-500">
                                No reapplications found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-4" id="paginationLinks">
            {{ $reapplications->links() }}
        </div>

    @endif

</div>

<!-- LOADING OVERLAY -->
<div id="sortLoadingOverlay" class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl px-8 py-6 flex items-center gap-4">
        <div class="w-6 h-6 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
        <span class="font-semibold text-gray-700">Loading...</span>
    </div>
</div>

<script>
    function showSortLoading() {
        document.getElementById('sortLoadingOverlay').classList.remove('hidden');
    }

    // Pagination links come from Laravel's own paginator view, not markup
    // this template controls directly — event delegation catches clicks on
    // them without needing to override that view just for a loading state.
    document.getElementById('paginationLinks')?.addEventListener('click', function (e) {
        if (e.target.closest('a')) {
            showSortLoading();
        }
    });
</script>

@endsection

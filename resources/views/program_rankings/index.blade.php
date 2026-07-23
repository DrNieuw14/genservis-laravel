@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🏆 Program Rankings
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Consolidated exam rankings per program, cross-checked against the applicant roster.
            </p>
        </div>

        <div class="flex gap-2">
            @if($selectedYear)
                <a href="{{ route('program-rankings.admitted-report', $selectedYear->id) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow whitespace-nowrap">
                    📄 Admitted Report
                </a>
            @endif

            <a href="{{ route('program-rankings.import') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow whitespace-nowrap">
                📥 Upload Raw Data
            </a>
        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg mb-6 text-sm">
        No passing cutoff or per-program quota has been set yet — these are ranked lists only
        (highest Grade first, matching the source sheet's own ordering), not a pass/fail decision.
    </div>

    @if($years->count() > 1)
        <form method="GET" class="mb-6 flex items-center gap-3">
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

    @elseif($programCounts->isEmpty())

        <p class="text-gray-500 text-center py-10">No rankings uploaded yet for {{ $selectedYear->label }}.</p>

    @else

        <a href="{{ route('program-rankings.all', $selectedYear->id) }}"
           class="block border-2 border-blue-300 bg-blue-50 rounded-lg p-5 hover:shadow-md hover:border-blue-500 transition mb-4">

            <p class="text-xl font-bold text-blue-800">📋 All Exam Takers</p>
            <p class="text-sm text-blue-600 mt-1">Every program combined, searchable</p>
            <p class="text-2xl font-semibold text-blue-700 mt-3">{{ $totalCount }}</p>
            <p class="text-xs text-blue-400">examinees</p>

        </a>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            @foreach($programCounts as $p)

                <a href="{{ route('program-rankings.show', [$selectedYear->id, $p['code']]) }}"
                   class="border rounded-lg p-5 hover:shadow-md hover:border-green-400 transition block">

                    <p class="text-xl font-bold text-gray-800">{{ $p['code'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $p['program'] }}</p>
                    <p class="text-2xl font-semibold text-green-700 mt-3">{{ $p['count'] }}</p>
                    <p class="text-xs text-gray-400">examinees</p>

                    @if($p['quota'])
                        <p class="text-sm text-blue-600 mt-2 font-semibold">🎯 {{ $p['quota'] }} slots</p>
                    @else
                        <p class="text-xs text-gray-400 mt-2">No capacity set yet</p>
                    @endif

                </a>

            @endforeach

        </div>

    @endif

</div>

@endsection

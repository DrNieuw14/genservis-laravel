@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🎓 {{ $year->label }} — Applicant Roster
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $applicants->total() }} applicant(s) on record.
            </p>
        </div>

        <div class="flex gap-2">
            <x-back-button :href="route('admission-years.index')" />

            <a href="{{ route('admission-applicants.import', $year->id) }}"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow whitespace-nowrap">
                📥 Upload Roster
            </a>
        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('importSkipped') && count(session('importSkipped')) > 0)
        <div class="bg-red-50 border border-red-300 text-red-800 p-4 mb-4 rounded-lg">
            <p class="font-semibold mb-2">⚠ {{ count(session('importSkipped')) }} row(s) were skipped and NOT imported:</p>
            <ul class="list-disc ml-5 text-sm max-h-40 overflow-y-auto">
                @foreach(session('importSkipped') as $skip)
                    <li>Control No. {{ $skip['control_number'] }} — {{ $skip['reason'] }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('importDuplicates') && count(session('importDuplicates')) > 0)
        <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 p-4 mb-6 rounded-lg">
            <p class="font-semibold mb-2">🔎 {{ count(session('importDuplicates')) }} possible duplicate application(s) found (same name/birthdate, different Control Number) — imported, but worth reviewing:</p>
            <ul class="list-disc ml-5 text-sm max-h-40 overflow-y-auto">
                @foreach(session('importDuplicates') as $dup)
                    <li>{{ $dup['name'] }} — Control No. {{ $dup['control_number'] }} matches existing Control No. {{ $dup['matches_control_number'] }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" class="mb-6 flex flex-col md:flex-row gap-3">

        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or control number"
            class="w-full border rounded-lg p-3">

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow whitespace-nowrap">
            🔍 Search
        </button>

        @if($search)
            <a href="{{ route('admission-applicants.index', $year->id) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg shadow text-center">
                Clear
            </a>
        @endif

    </form>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Control No.</th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Program</th>
                    <th class="p-3 text-left">Municipality</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($applicants as $applicant)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3">{{ $applicant->control_number }}</td>

                        <td class="p-3 font-semibold">{{ $applicant->fullName() }}</td>

                        <td class="p-3">{{ $applicant->program ?: '-' }}</td>

                        <td class="p-3">{{ $applicant->municipality ?: '-' }}</td>

                        <td class="p-3 text-center">
                            <a href="{{ route('admission-applicants.show', [$year->id, $applicant->id]) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                View
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            No applicants yet. Upload a roster to get started.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $applicants->links() }}
    </div>

</div>

@endsection

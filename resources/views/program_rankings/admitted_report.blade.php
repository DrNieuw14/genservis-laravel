@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📄 Admitted Students Report
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $year->label }} — {{ $groups->sum(fn($g) => $g['admitted']->count()) }} admitted across {{ $groups->count() }} program(s)
            </p>
        </div>

        <div class="flex gap-2">
            <x-back-button :href="route('program-rankings.index', ['admission_year_id' => $year->id])" />

            <a href="{{ route('program-rankings.admitted-report.print', $year->id) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>
        </div>

    </div>

    @if($groups->isEmpty())

        <p class="text-gray-500 text-center py-10">
            No program has a capacity set yet — set Sections × Students per Section on a program's ranking page first.
        </p>

    @else

        @foreach($groups as $group)

            <div class="border rounded-lg mb-8 overflow-hidden">

                <div class="bg-gray-100 px-5 py-3 flex justify-between items-center">
                    <div>
                        <span class="text-lg font-bold text-gray-800">{{ $group['code'] }}</span>
                        <span class="text-sm text-gray-500 ml-2">{{ $group['program'] }}</span>
                    </div>
                    <span class="text-sm font-semibold text-green-700">
                        {{ $group['admitted']->count() }} admitted (quota {{ $group['quota'] }})
                        @if($group['admitted']->count() > $group['quota'])
                            <span class="text-gray-400 font-normal">— includes ties</span>
                        @endif
                    </span>
                </div>

                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-2 text-center">Rank</th>
                            <th class="p-2 text-left">Code</th>
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2 text-left">Address</th>
                            <th class="p-2 text-center">Grade</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($group['admitted'] as $i => $r)
                            <tr>
                                <td class="p-2 text-center">{{ $i + 1 }}</td>
                                <td class="p-2">{{ $r->code ?? '-' }}</td>
                                <td class="p-2 font-semibold">{{ $r->examinee_name }}</td>
                                <td class="p-2">{{ $r->address ?? '-' }}</td>
                                <td class="p-2 text-center">{{ $r->grade }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        @endforeach

    @endif

</div>

@endsection

@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🩺 My Health Consultations
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Your visit history with Health Services.
            </p>
        </div>

    </div>

    @if($consultations->isEmpty())

        <p class="text-gray-500 text-center py-10">You have no health consultation records yet.</p>

    @else

        <div class="overflow-x-auto border rounded-lg">

            <table class="w-full">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Case No.</th>
                        <th class="p-3 text-center">Date</th>
                        <th class="p-3 text-left">Chief Complaint</th>
                        <th class="p-3 text-left">Diagnosis</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach($consultations as $consultation)

                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-semibold">{{ $consultation->case_no }}</td>
                            <td class="p-3 text-center">{{ $consultation->consultation_date->format('M d, Y') }}</td>
                            <td class="p-3">{{ $consultation->chief_complaint ?: '-' }}</td>
                            <td class="p-3">{{ $consultation->diagnosis ?: '-' }}</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('health-consultations.show', $consultation->id) }}" class="text-blue-600 hover:underline text-sm">
                                    📋 View
                                </a>
                            </td>
                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>

@endsection

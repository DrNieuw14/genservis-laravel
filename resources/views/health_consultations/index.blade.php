@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🩺 Health Consultations
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Campus Health Services — patient consultation records.
            </p>
        </div>

        <a href="{{ route('health-consultations.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow whitespace-nowrap">
            ➕ New Consultation
        </a>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-6 flex flex-col md:flex-row gap-3">

        <input type="text" name="search" value="{{ $search }}" placeholder="Search by patient name, case no., or complaint"
            class="w-full border rounded-lg p-3">

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow whitespace-nowrap">
            🔍 Search
        </button>

        @if($search)
            <a href="{{ route('health-consultations.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg shadow text-center">
                Clear
            </a>
        @endif

    </form>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Case No.</th>
                    <th class="p-3 text-left">Patient</th>
                    <th class="p-3 text-left">Chief Complaint</th>
                    <th class="p-3">Date</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($consultations as $consultation)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3">{{ $consultation->case_no }}</td>

                        <td class="p-3 font-semibold">{{ $consultation->patient_name }}</td>

                        <td class="p-3">{{ \Illuminate\Support\Str::limit($consultation->chief_complaint, 60) ?: '-' }}</td>

                        <td class="p-3 text-center">{{ $consultation->consultation_date->format('M d, Y') }}</td>

                        <td class="p-3 text-center">
                            <a href="{{ route('health-consultations.show', $consultation->id) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                View
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            No consultation records yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $consultations->links() }}
    </div>

</div>

@endsection

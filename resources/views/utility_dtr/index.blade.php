@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            🗓️ Monthly DTR
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Daily Time Record per staff member — built from Attendance, Overtime, and approved Leave.
        </p>
    </div>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Staff</th>
                    <th class="p-3 text-left">Position</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($staff as $person)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3 font-semibold">{{ $person->fullname }}</td>

                        <td class="p-3">{{ $person->positionRecord->position_name ?? '-' }}</td>

                        <td class="p-3 text-center">
                            <a href="{{ route('utility-dtr.show', $person->id) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                View DTR
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="p-6 text-center text-gray-500">
                            No Utility & Maintenance Staff on record yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

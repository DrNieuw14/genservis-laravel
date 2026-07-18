@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧑‍💼 {{ $personnel->fullname }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $personnel->employee_id }}
                @if($personnel->departmentRecord)
                    &nbsp;•&nbsp; {{ $personnel->departmentRecord->department_name }}
                @endif
                @if($personnel->positionRecord)
                    &nbsp;•&nbsp; {{ $personnel->positionRecord->position_name }}
                @endif
            </p>
        </div>

        <x-back-button :href="route('walkin.history')" />

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

        <div class="border rounded-xl p-5 bg-blue-50">
            <p class="text-sm text-blue-700 font-semibold">Total Walk-In Issuances</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalIssuances }}</p>
        </div>

        <div class="border rounded-xl p-5 bg-green-50">
            <p class="text-sm text-green-700 font-semibold">Total Items Issued</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalItemsIssued }}</p>
        </div>

    </div>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reference No.</th>
                    <th class="p-3 text-left">Destination</th>
                    <th class="p-3 text-left">Materials Requested</th>
                    <th class="p-3 text-left">Room</th>
                    <th class="p-3 text-left">Purpose</th>
                    <th class="p-3 text-left">Issued By</th>
                    <th class="p-3 text-left">Date Issued</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($requests as $request)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3">
                            {{ $request->reference_no }}
                        </td>

                        <td class="p-3">
                            {{ $request->department->department_name ?? '-' }}
                        </td>

                        <td class="p-3">
                            @foreach($request->items as $item)
                                <div>
                                    {{ $item->material->name ?? 'Deleted Material' }}
                                    <span class="text-gray-500">× {{ $item->quantity }} {{ $item->unit }}</span>
                                </div>
                            @endforeach
                        </td>

                        <td class="p-3">
                            {{ $request->room ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ $request->purpose ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ $request->issuer->username ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($request->issued_at)->format('M d, Y h:i A') }}
                        </td>

                        <td class="p-3 text-center">
                            <a
                                href="{{ route('walkin.show', $request->id) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                View
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">
                            No walk-in issuances on record for this employee yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>

</div>

@endsection

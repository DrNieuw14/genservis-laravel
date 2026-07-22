@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧾 My Property Accountability
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                ICS/PAR slips for property issued to you.
            </p>
        </div>

    </div>

    @if($issuances->isEmpty())

        <p class="text-gray-500 text-center py-10">No property has been issued to you yet.</p>

    @else

        <div class="overflow-x-auto border rounded-lg">

            <table class="w-full">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Slip No.</th>
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Room</th>
                        <th class="p-3 text-center">Items</th>
                        <th class="p-3 text-center">Total Value (₱)</th>
                        <th class="p-3 text-center">Date Issued</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach($issuances as $issuance)

                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-semibold">{{ $issuance->slip_no }}</td>
                            <td class="p-3 text-center">
                                <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $issuance->isIcs() ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $issuance->isIcs() ? 'ICS' : 'PAR' }}
                                </span>
                            </td>
                            <td class="p-3">{{ $issuance->room->room_name ?? '-' }}</td>
                            <td class="p-3 text-center">{{ $issuance->items->count() }}</td>
                            <td class="p-3 text-center">{{ number_format($issuance->totalValue(), 2) }}</td>
                            <td class="p-3 text-center">{{ $issuance->issued_at->format('M d, Y') }}</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('property-issuances.show', $issuance->id) }}" class="text-blue-600 hover:underline text-sm">
                                    📋 View
                                </a>
                            </td>
                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="mt-4">
            {{ $issuances->links() }}
        </div>

    @endif

</div>

@endsection

@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🏀 My Borrowed Equipment
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Sports equipment you've requested to borrow, and their status.
            </p>
        </div>

        <a href="{{ url('/material-request') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow">
            🏀 Borrow Equipment
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Ref #</th>
                    <th class="p-3 text-left">Equipment</th>
                    <th class="p-3 text-center">Qty</th>
                    <th class="p-3 text-left">Purpose</th>
                    <th class="p-3 text-center">Expected Return</th>
                    <th class="p-3 text-center">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($borrows as $borrow)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-mono text-sm">{{ $borrow->borrow_number }}</td>
                        <td class="p-3">{{ $borrow->equipment->name }}</td>
                        <td class="p-3 text-center">{{ $borrow->quantity }}</td>
                        <td class="p-3">{{ $borrow->purpose }}</td>
                        <td class="p-3 text-center">{{ $borrow->expected_return_date->format('M d, Y') }}</td>
                        <td class="p-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $borrow->statusBadgeClass() }}">
                                {{ ucfirst($borrow->displayStatus()) }}
                            </span>

                            @if($borrow->status === 'rejected' && $borrow->rejection_reason)
                                <p class="text-xs text-gray-500 mt-1">{{ $borrow->rejection_reason }}</p>
                            @endif
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            You haven't borrowed any sports equipment yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

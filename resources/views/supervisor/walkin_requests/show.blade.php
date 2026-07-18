@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold text-gray-800">
            📦 Walk-In Issuance Details
        </h2>

        <div class="flex gap-2">

            <x-back-button :href="route('walkin.history')" />

            <a
                href="{{ route('walkin.print', $request->id) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">

                🖨 Print

            </a>

        </div>

    </div>

    <!-- Transaction Info -->

    <div class="border rounded-lg p-5 bg-blue-50 mb-6">

        <h3 class="font-bold text-blue-700 mb-4">
            Transaction Information
        </h3>

        <div class="grid grid-cols-2 gap-4">

            <div>
                <strong>Reference No:</strong>
                {{ $request->reference_no }}
            </div>

            <div>
                <strong>Date Issued:</strong>
                {{ \Carbon\Carbon::parse($request->issued_at)->format('M d, Y h:i A') }}
            </div>

            <div>
                <strong>Source:</strong>
                Centralized Stockroom
            </div>

            <div>
                <strong>Destination:</strong>
                {{ $request->department->department_name ?? '-' }}
            </div>

            <div>
                <strong>Requested By:</strong>
                {{ $request->requestor_name }}
            </div>

            <div>
                <strong>Issued By:</strong>
                {{ $request->issuer->username ?? '-' }}
            </div>

            <div>
                <strong>Room:</strong>
                {{ $request->room }}
            </div>

            <div>
                <strong>Purpose:</strong>
                {{ $request->purpose }}
            </div>

        </div>

    </div>

    <!-- Materials -->

    <div class="border rounded-lg overflow-hidden">

        <div class="bg-gray-100 p-4 font-bold">
            Issued Materials
        </div>

        <table class="w-full">

            <thead class="bg-gray-50">

                <tr>
                    <th class="p-3 text-left">Material</th>
                    <th class="p-3 text-center">Qty</th>
                    <th class="p-3 text-center">Unit</th>
                    <th class="p-3 text-center">Before</th>
                    <th class="p-3 text-center">After</th>
                </tr>

            </thead>

            <tbody>

                @foreach($request->items as $item)

                <tr class="border-t">

                    <td class="p-3">
                        {{ $item->material->name ?? '-' }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $item->quantity }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $item->unit }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $item->stock_before }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $item->stock_after }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
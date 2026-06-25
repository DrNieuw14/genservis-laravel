@extends('layouts.app')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
        Walk-In Issuance History
    </h2>

    <table class="w-full border">

        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Reference No.</th>
                <th class="p-2">Source</th>
                <th class="p-2">Destination</th>
                <th class="p-2">Employee</th>
                <th class="p-2">Room</th>
                <th class="p-2">Purpose</th>
                <th class="p-2">Issued By</th>
                <th class="p-2">Date Issued</th>
                <th class="p-2">Action</th>
            </tr>
        </thead>

        <tbody>

        @foreach($requests as $request)

            <tr class="border-t hover:bg-gray-50">

                <td class="p-2">
                    {{ $request->reference_no }}
                </td>

                <td class="p-2">
                    Centralized Stockroom
                </td>

                <td class="p-2">
                    {{ $request->department->department_name ?? '-' }}
                </td>

                <td class="p-2">
                    {{ $request->requestor_name ?? '-' }}
                </td>

                <td class="p-2">
                    {{ $request->room ?? '-' }}
                </td>

                <td class="p-2">
                    {{ $request->purpose ?? '-' }}
                </td>

                <td class="p-2">
                    {{ $request->issuer->username ?? '-' }}
                </td>

                <td class="p-2">
                    {{ \Carbon\Carbon::parse($request->issued_at)->format('M d, Y h:i A') }}
                </td>

                <td class="p-2 text-center">
                    <a
                        href="{{ route('walkin.show', $request->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">

                        View

                    </a>
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>

</div>

@endsection
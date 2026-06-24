@extends('layouts.app')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
        Walk-In Issuance History
    </h2>

    <table class="w-full border">

        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Reference</th>
                <th class="p-2">Requestor</th>
                <th class="p-2">Department</th>
                <th class="p-2">Issued By</th>
                <th class="p-2">Date</th>
            </tr>
        </thead>

        <tbody>

        @foreach($requests as $request)

            <tr class="border-t">

                <td class="p-2">
                    {{ $request->reference_no }}
                </td>

                <td class="p-2">
                    {{ $request->requestor_name }}
                </td>

                <td class="p-2">
                    {{ $request->department->department_name }}
                </td>

                <td class="p-2">
                    {{ $request->issuer->username ?? '-' }}
                </td>

                <td class="p-2">
                    {{ $request->issued_at }}
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
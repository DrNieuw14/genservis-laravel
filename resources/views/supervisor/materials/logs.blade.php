@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto mt-8">

    <h2 class="text-3xl font-bold text-white mb-6">
        📜 Material Logs
    </h2>

    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-gradient-to-r from-green-500 to-blue-600 text-white">

                <tr>
                    <th class="p-4 text-left">Material</th>
                    <th class="p-4 text-left">Action</th>
                    <th class="p-4 text-left">Quantity</th>
                    <th class="p-4 text-left">User</th>
                    <th class="p-4 text-left">Remarks</th>
                    <th class="p-4 text-left">Date</th>
                </tr>

            </thead>

            <tbody>

                @forelse($logs as $log)

                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-4">
                            {{ $log->material->name ?? '-' }}
                        </td>

                        <td class="p-4">
                            {{ $log->action }}
                        </td>

                        <td class="p-4">
                            {{ $log->quantity }}
                        </td>

                        <td class="p-4">
                            {{ $log->user->username ?? '-' }}
                        </td>

                        <td class="p-4">
                            {{ $log->remarks }}
                        </td>

                        <td class="p-4">
                            {{ $log->created_at->format('M d, Y h:i A') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center py-8 text-gray-500">

                            No logs found

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
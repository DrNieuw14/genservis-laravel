@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- PAGE HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            📜 Material Logs
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Inventory activity and audit trail history
        </p>

    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <!-- TOTAL LOGS -->
        <div class="bg-blue-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Total Logs</div>
            <div class="text-4xl font-bold">{{ $logs->count() }}</div>
        </div>

        <!-- STOCK IN -->
        <div class="bg-green-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Stock In</div>
            <div class="text-4xl font-bold">{{ $logs->where('action', 'stock_in')->count() }}</div>
        </div>

        <!-- STOCK OUT -->
        <div class="bg-red-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Stock Out</div>
            <div class="text-4xl font-bold">{{ $logs->where('action', 'stock_out')->count() }}</div>
        </div>

    </div>

    <!-- SEARCH + FILTER -->
    <form method="GET"
        action="{{ route('materials.logs') }}"
        class="mb-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

            <!-- SEARCH -->
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search material, user, or action..."
                class="border border-gray-300 rounded-lg p-3 text-lg focus:ring-2 focus:ring-blue-400">

            <!-- DATE FILTER -->
            <select name="date_filter"
                    class="border border-gray-300 rounded-lg p-3 text-lg focus:ring-2 focus:ring-blue-400">

                <option value="">All Dates</option>

                <option value="today"
                    {{ request('date_filter') == 'today' ? 'selected' : '' }}>
                    Today
                </option>

                <option value="week"
                    {{ request('date_filter') == 'week' ? 'selected' : '' }}>
                    This Week
                </option>

                <option value="month"
                    {{ request('date_filter') == 'month' ? 'selected' : '' }}>
                    This Month
                </option>

            </select>

            <!-- BUTTON -->
            <button
                class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold text-lg">

                Filter Logs

            </button>

        </div>

    </form>

    <!-- TABLE -->
    <div class="border rounded-lg overflow-hidden">

      <div class="overflow-x-auto">

        <table class="w-full text-lg">

            <thead class="bg-gray-100">

                <tr>
                    <th class="p-4 text-left text-gray-800">Material</th>
                    <th class="p-4 text-left text-gray-800">Action</th>
                    <th class="p-4 text-left text-gray-800">Quantity</th>
                    <th class="p-4 text-left text-gray-800">User</th>
                    <th class="p-4 text-left text-gray-800">Remarks</th>
                    <th class="p-4 text-left text-gray-800">Date</th>
                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($logs as $log)

                    <tr class="hover:bg-gray-50 transition">

                        <!-- MATERIAL -->
                        <td class="p-4 font-medium">
                            {{ $log->material->name ?? '-' }}
                        </td>

                        <!-- ACTION -->
                        <td class="p-4">

                            @if($log->action == 'stock_in')

                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm">
                                    ⬆️ STOCK IN
                                </span>

                            @elseif($log->action == 'stock_out')

                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm">
                                    ⬇️ STOCK OUT
                                </span>

                            @else

                                <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-sm">
                                    {{ strtoupper($log->action) }}
                                </span>

                            @endif

                        </td>

                        <!-- QUANTITY -->
                        <td class="p-4">
                            {{ $log->quantity }}
                        </td>

                        <!-- USER -->
                        <td class="p-4">
                            {{ $log->user->username ?? 'System' }}
                        </td>

                        <!-- REMARKS -->
                        <td class="p-4">
                            {{ $log->remarks }}
                        </td>

                        <!-- DATE -->
                        <td class="p-4 text-sm text-gray-500">
                            {{ $log->created_at->format('M d, Y h:i A') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center text-gray-500 py-10">

                            No material logs found

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

      </div>

    </div>

</div>

@endsection
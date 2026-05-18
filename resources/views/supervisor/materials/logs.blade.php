@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto mt-8">

    <!-- PAGE HEADER -->
    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-3xl font-bold text-white flex items-center gap-2">
                📜 Material Logs
            </h2>

            <p class="text-gray-200 mt-1">
                Inventory activity and audit trail history
            </p>
        </div>

    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <!-- TOTAL LOGS -->
        <div class="bg-white rounded-2xl shadow-xl p-6">

            <h3 class="text-gray-500 text-sm">
                Total Logs
            </h3>

            <p class="text-3xl font-bold text-blue-600 mt-2">
                {{ $logs->count() }}
            </p>

        </div>

        <!-- STOCK IN -->
        <div class="bg-white rounded-2xl shadow-xl p-6">

            <h3 class="text-gray-500 text-sm">
                Stock In
            </h3>

            <p class="text-3xl font-bold text-green-500 mt-2">
                {{ $logs->where('action', 'stock_in')->count() }}
            </p>

        </div>

        <!-- STOCK OUT -->
        <div class="bg-white rounded-2xl shadow-xl p-6">

            <h3 class="text-gray-500 text-sm">
                Stock Out
            </h3>

            <p class="text-3xl font-bold text-red-500 mt-2">
                {{ $logs->where('action', 'stock_out')->count() }}
            </p>

        </div>

    </div>

    <!-- SEARCH + FILTER -->
    <div class="bg-white rounded-2xl shadow-xl p-4 mb-6">

        <form method="GET"
            action="{{ route('materials.logs') }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                <!-- SEARCH -->
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search material, user, or action..."
                    class="border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

                <!-- DATE FILTER -->
                <select name="date_filter"
                        class="border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

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
                    class="bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">

                    Filter Logs

                </button>

            </div>

        </form>

    </div>

    <!-- TABLE -->
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">

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

                    <tr class="border-b hover:bg-gray-50 transition">

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

@endsection
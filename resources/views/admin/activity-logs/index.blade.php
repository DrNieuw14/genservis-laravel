@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- PAGE HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            📜 Activity Logs
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Audit trail of actions taken across the system.
        </p>

    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

        <div class="bg-blue-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Total Logged Activities</div>
            <div class="text-4xl font-bold">{{ $totalLogs }}</div>
        </div>

        <div class="bg-green-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Today</div>
            <div class="text-4xl font-bold">{{ $todayLogs }}</div>
        </div>

        <div class="bg-purple-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Distinct Users</div>
            <div class="text-4xl font-bold">{{ $distinctUsers }}</div>
        </div>

        <div class="bg-orange-500 text-white rounded-xl p-5 shadow-lg">
            <div class="text-base">Most Active Module</div>
            <div class="text-2xl font-bold mt-1">{{ $mostActiveModule }}</div>
        </div>

    </div>

    <!-- FILTERS -->
    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="mb-6">

        <div class="flex flex-col md:flex-row gap-4">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search description, action, or user..."
                class="flex-1 rounded-lg border border-gray-300 px-4 py-3 text-lg focus:ring-green-500 focus:border-green-500">

            <select name="module"
                    class="rounded-lg border border-gray-300 px-4 py-3 text-lg focus:ring-green-500 focus:border-green-500">

                <option value="">All Modules</option>

                @foreach($modules as $module)

                    <option value="{{ $module }}"
                        {{ request('module') == $module ? 'selected' : '' }}>

                        {{ $module }}

                    </option>

                @endforeach

            </select>

            <input
                type="date"
                name="date_from"
                value="{{ request('date_from') }}"
                class="rounded-lg border border-gray-300 px-4 py-3 text-lg focus:ring-green-500 focus:border-green-500">

            <input
                type="date"
                name="date_to"
                value="{{ request('date_to') }}"
                class="rounded-lg border border-gray-300 px-4 py-3 text-lg focus:ring-green-500 focus:border-green-500">

            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition font-semibold text-lg">

                🔍 Filter

            </button>

        </div>

    </form>

    <!-- LOGS TABLE -->
    <div class="border rounded-lg overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-lg">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-6 py-3 text-left text-gray-800">Date / Time</th>
                        <th class="px-6 py-3 text-left text-gray-800">User</th>
                        <th class="px-6 py-3 text-left text-gray-800">Module</th>
                        <th class="px-6 py-3 text-left text-gray-800">Action</th>
                        <th class="px-6 py-3 text-left text-gray-800">Description</th>
                        <th class="px-6 py-3 text-left text-gray-800">IP Address</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200">

                @forelse($logs as $log)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $log->created_at->format('M d, Y h:i A') }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $log->user?->fullname ?? $log->user?->username ?? 'Unknown User' }}
                        </td>

                        <td class="px-6 py-4">

                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-base font-semibold">
                                {{ $log->module }}
                            </span>

                        </td>

                        <td class="px-6 py-4">
                            {{ $log->action }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $log->description }}
                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            {{ $log->ip_address }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center py-10 text-gray-500 text-lg">

                            No activity logs found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-6 bg-white border-t">

            {{ $logs->links() }}

        </div>

    </div>

</div>

@endsection

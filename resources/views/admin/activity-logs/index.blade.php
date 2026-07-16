@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->
    <div class="mb-8">

        <h1 class="text-3xl font-bold text-white flex items-center gap-3">
            📜 Activity Logs
        </h1>

        <p class="text-gray-200 mt-2">
            Audit trail of actions taken across the system.
        </p>

    </div>

    <!-- ========================================================= -->
    <!-- SUMMARY CARDS -->
    <!-- ========================================================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <p class="text-sm text-gray-500">Total Logged Activities</p>
            <h2 class="text-3xl font-bold mt-2">{{ $totalLogs }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <p class="text-sm text-gray-500">Today</p>
            <h2 class="text-3xl font-bold mt-2 text-blue-600">{{ $todayLogs }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <p class="text-sm text-gray-500">Distinct Users</p>
            <h2 class="text-3xl font-bold mt-2 text-green-600">{{ $distinctUsers }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <p class="text-sm text-gray-500">Most Active Module</p>
            <h2 class="text-xl font-bold mt-2 text-purple-600">{{ $mostActiveModule }}</h2>
        </div>

    </div>

    <!-- ========================================================= -->
    <!-- FILTERS -->
    <!-- ========================================================= -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">

        <form method="GET" action="{{ route('admin.activity-logs.index') }}">

            <div class="flex flex-col md:flex-row gap-4">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search description, action, or user..."
                    class="flex-1 rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">

                <select name="module"
                        class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">

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
                    class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">

                <input
                    type="date"
                    name="date_to"
                    value="{{ request('date_to') }}"
                    class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">

                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">

                    🔍 Filter

                </button>

            </div>

        </form>

    </div>

    <!-- ========================================================= -->
    <!-- LOGS TABLE -->
    <!-- ========================================================= -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-green-700 text-white">

                <tr>

                    <th class="px-6 py-3 text-left">Date / Time</th>
                    <th class="px-6 py-3 text-left">User</th>
                    <th class="px-6 py-3 text-left">Module</th>
                    <th class="px-6 py-3 text-left">Action</th>
                    <th class="px-6 py-3 text-left">Description</th>
                    <th class="px-6 py-3 text-left">IP Address</th>

                </tr>

            </thead>

            <tbody>

            @forelse($logs as $log)

                <tr class="border-b hover:bg-gray-50">

                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $log->created_at->format('M d, Y h:i A') }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $log->user?->fullname ?? $log->user?->username ?? 'Unknown User' }}
                    </td>

                    <td class="px-6 py-4">

                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
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
                        class="text-center py-10 text-gray-500">

                        No activity logs found.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        <div class="p-6">

            {{ $logs->links() }}

        </div>

    </div>

</div>

@endsection

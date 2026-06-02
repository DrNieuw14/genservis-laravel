@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- PAGE HEADER -->
    <div class="mb-8">
        <h2 class="text-5xl font-bold text-white flex items-center gap-3">
            📄 My Leave Requests
        </h2>

        <p class="text-white/80 mt-2 text-lg">
            Track your submitted leave requests and approval status.
        </p>
    </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            <div class="bg-gradient-to-r from-yellow-500 to-yellow-400
            text-white p-5 rounded-2xl shadow-xl
            hover:scale-105 transition">
                <h3 class="text-sm">Pending</h3>
                <p class="text-4xl font-bold">
                    {{ $leaves->where('status','Pending')->count() }}
                </p>
            </div>

            <div class="bg-gradient-to-r from-green-600 to-emerald-500
            text-white p-5 rounded-2xl shadow-xl
            hover:scale-105 transition">
                <h3 class="text-sm">Approved</h3>
                <p class="text-4xl font-bold">
                    {{ $leaves->where('status','Approved')->count() }}
                </p>
            </div>

            <div class="bg-gradient-to-r from-red-600 to-red-500
            text-white p-5 rounded-2xl shadow-xl
            hover:scale-105 transition">
                <h3 class="text-sm">Rejected</h3>
                <p class="text-4xl font-bold">
                    {{ $leaves->where('status','Rejected')->count() }}
                </p>
            </div>

        </div>

    <!-- ✅ SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 mb-4 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <table class="w-full text-sm">

            <thead class="bg-gradient-to-r from-green-600 to-blue-700 text-white">
                <tr>
                    <th class="p-3 text-left">Reason</th>
                    <th class="p-3 text-left">Start Date</th>
                    <th class="p-3 text-left">End Date</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Submitted</th>
                    <th class="p-3 text-left">Approved Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($leaves as $leave)
                    <tr class="border-b hover:bg-gray-50 transition">

                        <td class="p-3">
                            {{ $leave->reason }}
                        </td>

                        <td class="p-3">
                            {{ $leave->start_date }}
                        </td>

                        <td class="p-3">
                            {{ $leave->end_date }}
                        </td>

                        <td class="p-4">

                            @if($leave->status == 'Pending')

                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    ⏳ Pending
                                </span>

                            @elseif($leave->status == 'Approved')

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    ✅ Approved
                                </span>

                            @else

                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    ❌ Rejected
                                </span>

                            @endif

                        </td>

                        <td class="p-3">
                            {{ $leave->created_at->format('M d, Y') }}
                        </td>

                        <td class="p-3">
                            {{ $leave->approved_at ? \Carbon\Carbon::parse($leave->approved_at)->format('M d, Y h:i A') : '-' }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">
                            No leave requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>


@endsection
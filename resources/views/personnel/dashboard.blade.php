@extends('layouts.main')

@section('content')

<h3>Personnel Dashboard</h3>

<div class="mt-4">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    
        <!-- Attendance -->
        <div class="bg-blue-600 text-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-bold">Attendance</h3>
            <p class="text-sm">Manage attendance records</p>
        </div>

        <!-- Scheduling -->
        <div class="bg-green-600 text-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-bold">Scheduling</h3>
            <p class="text-sm">Manage schedules</p>
        </div>

        <!-- Leave -->
        <a href="{{ url('/leave') }}">
            <div class="bg-yellow-500 hover:bg-yellow-600 text-white p-6 rounded-xl shadow-lg transition cursor-pointer">
                <h3 class="text-lg font-bold">Leave</h3>
                <p class="text-sm">Apply leave requests</p>
            </div>
        </a>

        <!-- CTO -->
        <div class="bg-red-500 text-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-bold">CTO</h3>
            <p class="text-sm">Manage CTO balances</p>
        </div>

    </div>

</div>

@endsection
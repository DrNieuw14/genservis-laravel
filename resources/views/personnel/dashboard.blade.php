<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Personnel Dashboard
    </h2>
</x-slot>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">

    <!-- Attendance -->
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-blue-500 cursor-pointer">
        <h3 class="font-bold text-blue-600">Attendance</h3>
        <p class="text-sm text-gray-500">Manage attendance records</p>
    </div>

    <!-- Scheduling -->
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-green-500 cursor-pointer">
        <h3 class="font-bold text-green-600">Scheduling</h3>
        <p class="text-sm text-gray-500">Manage schedules</p>
    </div>

    <!-- Leave -->
    <a href="{{ url('/leave') }}">
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-yellow-500 cursor-pointer">
            <h3 class="font-bold text-yellow-600">Leave</h3>
            <p class="text-sm text-gray-500">Apply leave requests</p>
        </div>
    </a>

    <!-- CTO -->
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-red-500 cursor-pointer">
        <h3 class="font-bold text-red-600">CTO</h3>
        <p class="text-sm text-gray-500">Manage CTO balances</p>
    </div>

</div>

</x-app-layout>
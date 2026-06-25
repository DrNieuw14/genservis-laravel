@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- ==========================================
    REPORTS CENTER HEADER
    ========================================== -->

    <div class="mb-10">

        <h1 class="text-4xl font-bold text-white mt-4">
            📊 Reports Dashboard
        </h1>

        <p class="text-blue-100 text-lg mt-3 max-w-4xl leading-relaxed">
            Access administrative and operational reports for inventory,
            personnel, attendance, and leave management.
            Generate professional reports to support decision-making,
            monitoring, auditing, and campus operations.
        </p>

    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <h2 class="text-xl font-bold mb-2">
                📦 Inventory Reports
            </h2>

            <p class="text-gray-500 text-sm">
                Inventory status, stock levels, expiration, and department reports.
            </p>

            <a href="{{ route('reports.inventory') }}"
            class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Open
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">

            <h2 class="text-xl font-bold mb-2">
                👥 Personnel Reports
            </h2>

            <p class="text-sm text-gray-500">
                Coming Soon
            </p>

        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">

            <h2 class="text-xl font-bold mb-2">
                📅 Attendance Reports
            </h2>

            <p class="text-sm text-gray-500">
                Coming Soon
            </p>

        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">

            <h2 class="text-xl font-bold mb-2">
                📝 Leave Reports
            </h2>

            <p class="text-sm text-gray-500">
                Coming Soon
            </p>

        </div>

    </div>

</div>

@endsection
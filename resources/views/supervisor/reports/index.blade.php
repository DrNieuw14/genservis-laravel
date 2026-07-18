@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- REPORTS CENTER HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            📊 Reports Dashboard
        </h2>

        <p class="text-gray-500 text-lg mt-2 max-w-4xl leading-relaxed">
            Access administrative and operational reports for inventory,
            personnel, attendance, and leave management.
            Generate professional reports to support decision-making,
            monitoring, auditing, and campus operations.
        </p>

    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition">
            <h3 class="text-xl font-bold mb-2 text-gray-800">
                📦 Inventory Reports
            </h3>

            <p class="text-gray-500 text-base">
                Inventory status, stock levels, expiration, and department reports.
            </p>

            <a href="{{ route('reports.inventory') }}"
            class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Open
            </a>
        </div>

        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition">

            <h3 class="text-xl font-bold mb-2 text-gray-800">
                👥 Personnel Reports
            </h3>

            <p class="text-base text-gray-500">
                Coming Soon
            </p>

        </div>

        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition">

            <h3 class="text-xl font-bold mb-2 text-gray-800">
                📅 Attendance Reports
            </h3>

            <p class="text-base text-gray-500">
                Coming Soon
            </p>

        </div>

        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition">

            <h3 class="text-xl font-bold mb-2 text-gray-800">
                📝 Leave Reports
            </h3>

            <p class="text-base text-gray-500">
                Coming Soon
            </p>

        </div>

    </div>

</div>

@endsection
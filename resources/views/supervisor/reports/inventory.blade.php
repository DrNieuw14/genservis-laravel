@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            📦 Inventory Reporting Center
        </h2>

        <p class="text-gray-500 text-lg mt-2 max-w-4xl leading-relaxed">
            Generate executive and operational inventory reports to support planning,
            monitoring, and inventory management.
        </p>

    </div>

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">

        <!-- Executive Summary -->
        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition border-l-4 border-l-blue-600">

            <h3 class="text-xl font-bold text-gray-800">
                📄 Executive Summary
            </h3>

            <p class="text-gray-500 mt-2">
                Overall inventory statistics for administrators.
            </p>

            <a href="{{ route('inventory.executive') }}"
            class="inline-block mt-5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Full Inventory Summary -->
        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition border-l-4 border-l-green-600">

            <h3 class="text-xl font-bold text-gray-800">
                📋 Full Inventory Summary
            </h3>

            <p class="text-gray-500 mt-2">
                Complete inventory report with all sections.
            </p>

            <a href="{{ route('inventory.summary') }}"
            class="inline-block mt-5 bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Critical Stock -->
        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition border-l-4 border-l-red-600">

            <h3 class="text-xl font-bold text-gray-800">
                🚨 Critical Stock Report
            </h3>

            <p class="text-gray-500 mt-2">
                Materials currently at critical inventory levels.
            </p>

            <a href="{{ route('inventory.critical') }}"
            class="inline-block mt-5 bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Low Stock -->
        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition border-l-4 border-l-yellow-500">

            <h3 class="text-xl font-bold text-gray-800">
                ⚠️ Low Stock Report
            </h3>

            <p class="text-gray-500 mt-2">
                Materials approaching reorder level.
            </p>

            <a href="{{ route('inventory.low') }}"
            class="inline-block mt-5 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Out of Stock -->
        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition border-l-4 border-l-gray-700">

            <h3 class="text-xl font-bold text-gray-800">
                ❌ Out of Stock Report
            </h3>

            <p class="text-gray-500 mt-2">
                Materials with zero available stock.
            </p>

            <a href="{{ route('inventory.out') }}"
            class="inline-block mt-5 bg-gray-700 hover:bg-gray-800 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Expiration -->
        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition border-l-4 border-l-purple-600">

            <h3 class="text-xl font-bold text-gray-800">
                🧪 Expiration Report
            </h3>

            <p class="text-gray-500 mt-2">
                Expiring and expired inventory batches.
            </p>

            <a href="{{ route('inventory.expiration') }}"
            class="inline-block mt-5 bg-purple-600 hover:bg-purple-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Department Summary -->
        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition border-l-4 border-l-indigo-600">

            <h3 class="text-xl font-bold text-gray-800">
                🏢 Department Summary
            </h3>

            <p class="text-gray-500 mt-2">
                Inventory distribution by department.
            </p>

            <a href="{{ route('inventory.department') }}"
            class="inline-block mt-5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Purchase Recommendations -->
        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition border-l-4 border-l-teal-600">

            <h3 class="text-xl font-bold text-gray-800">
                🛒 Purchase Recommendations
            </h3>

            <p class="text-gray-500 mt-2">
                What to buy right now, and what's expiring this month.
            </p>

            <a href="{{ route('inventory.purchase-recommendations') }}"
            class="inline-block mt-5 bg-teal-600 hover:bg-teal-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Frequently Requested Items -->
        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition border-l-4 border-l-blue-600">

            <h3 class="text-xl font-bold text-gray-800">
                🔥 Frequently Requested Items
            </h3>

            <p class="text-gray-500 mt-2">
                Materials with the highest demand — keep these well stocked.
            </p>

            <a href="{{ route('inventory.frequently-requested') }}"
            class="inline-block mt-5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Non-Movable Items -->
        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:shadow-md transition border-l-4 border-l-gray-700">

            <h3 class="text-xl font-bold text-gray-800">
                📦 Non-Movable Items
            </h3>

            <p class="text-gray-500 mt-2">
                Materials with zero request activity — candidates for reassignment.
            </p>

            <a href="{{ route('inventory.non-movable') }}"
            class="inline-block mt-5 bg-gray-700 hover:bg-gray-800 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

    </div>

</div>

@endsection

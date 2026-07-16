@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <h1 class="text-4xl font-bold text-white mb-2">
    📦 Inventory Reporting Center
    </h1>

    <p class="text-blue-100 text-lg mb-8 max-w-3xl">
        Generate executive and operational inventory reports to support planning, monitoring, and inventory management.
    </p>

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">

        <!-- Executive Summary -->
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border-l-4 border-blue-600">

            <h2 class="text-xl font-bold">
                📄 Executive Summary
            </h2>

            <p class="text-gray-500 mt-2">
                Overall inventory statistics for administrators.
            </p>

            <a href="{{ route('inventory.executive') }}"
            class="inline-block mt-5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Full Inventory Summary -->
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border-l-4 border-green-600">

            <h2 class="text-xl font-bold">
                📋 Full Inventory Summary
            </h2>

            <p class="text-gray-500 mt-2">
                Complete inventory report with all sections.
            </p>

            <a href="{{ route('inventory.summary') }}"
            class="inline-block mt-5 bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Critical Stock -->
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border-l-4 border-red-600">

            <h2 class="text-xl font-bold">
                🚨 Critical Stock Report
            </h2>

            <p class="text-gray-500 mt-2">
                Materials currently at critical inventory levels.
            </p>

            <a href="{{ route('inventory.critical') }}"
            class="inline-block mt-5 bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Low Stock -->
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border-l-4 border-yellow-500">

            <h2 class="text-xl font-bold">
                ⚠️ Low Stock Report
            </h2>

            <p class="text-gray-500 mt-2">
                Materials approaching reorder level.
            </p>

            <a href="{{ route('inventory.low') }}"
            class="inline-block mt-5 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Out of Stock -->
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border-l-4 border-gray-700">

            <h2 class="text-xl font-bold">
                ❌ Out of Stock Report
            </h2>

            <p class="text-gray-500 mt-2">
                Materials with zero available stock.
            </p>

            <a href="{{ route('inventory.out') }}"
            class="inline-block mt-5 bg-gray-700 hover:bg-gray-800 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Expiration -->
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border-l-4 border-purple-600">

            <h2 class="text-xl font-bold">
                🧪 Expiration Report
            </h2>

            <p class="text-gray-500 mt-2">
                Expiring and expired inventory batches.
            </p>

            <a href="{{ route('inventory.expiration') }}"
            class="inline-block mt-5 bg-purple-600 hover:bg-purple-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

        <!-- Department Summary -->
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border-l-4 border-indigo-600">

            <h2 class="text-xl font-bold">
                🏢 Department Summary
            </h2>

            <p class="text-gray-500 mt-2">
                Inventory distribution by department.
            </p>

            <a href="{{ route('inventory.department') }}"
            class="inline-block mt-5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2 rounded transition">
                📄 Open Report
            </a>

        </div>

    </div>

</div>

@endsection
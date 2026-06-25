@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <h1 class="text-3xl font-bold mb-2">
        📦 Inventory Reports
    </h1>

    <p class="text-gray-600 mb-8">
        Select an inventory report to preview or print.
    </p>

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">

        <!-- Executive Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold">
                📄 Executive Summary
            </h2>

            <p class="text-gray-500 mt-2">
                Overall inventory statistics for administrators.
            </p>

            <a href="{{ route('inventory.summary') }}"
               class="inline-block mt-5 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Open
            </a>
        </div>

        <!-- Full Inventory -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold">
                📋 Full Inventory Summary
            </h2>

            <p class="text-gray-500 mt-2">
                Complete inventory report with all sections.
            </p>

            <a href="{{ route('inventory.summary') }}"
               class="inline-block mt-5 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Open
            </a>
        </div>

        <!-- Critical Stock -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold">
                🚨 Critical Stock Report
            </h2>

            <p class="text-gray-500 mt-2">
                Materials at critical inventory levels.
            </p>

            <a href="#"
               class="inline-block mt-5 bg-red-600 text-white px-4 py-2 rounded">
                Coming Soon
            </a>
        </div>

        <!-- Low Stock -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold">
                ⚠️ Low Stock Report
            </h2>

            <p class="text-gray-500 mt-2">
                Materials approaching reorder level.
            </p>

            <a href="#"
               class="inline-block mt-5 bg-yellow-600 text-white px-4 py-2 rounded">
                Coming Soon
            </a>
        </div>

        <!-- Out of Stock -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold">
                ❌ Out of Stock Report
            </h2>

            <p class="text-gray-500 mt-2">
                Materials with zero available stock.
            </p>

            <a href="#"
               class="inline-block mt-5 bg-gray-700 text-white px-4 py-2 rounded">
                Coming Soon
            </a>
        </div>

        <!-- Expiration -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold">
                🧪 Expiration Report
            </h2>

            <p class="text-gray-500 mt-2">
                Expiring and expired inventory batches.
            </p>

            <a href="#"
               class="inline-block mt-5 bg-purple-600 text-white px-4 py-2 rounded">
                Coming Soon
            </a>
        </div>

        <!-- Department -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold">
                🏢 Department Summary
            </h2>

            <p class="text-gray-500 mt-2">
                Inventory distribution by department.
            </p>

            <a href="#"
               class="inline-block mt-5 bg-indigo-600 text-white px-4 py-2 rounded">
                Coming Soon
            </a>
        </div>

    </div>

</div>

@endsection
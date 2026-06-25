@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- =========================
        REPORT HEADER
    ========================== --}}
    <div class="flex justify-between items-start mb-8">

        <div>

            <h1 class="text-4xl font-bold text-white">

                @yield('report-title')

            </h1>

            <p class="text-blue-100 text-lg mt-2">

                @yield('report-description')

            </p>

            <div class="mt-5 text-blue-100 space-y-1">

                <p>
                    <strong>Office:</strong>
                    General Services Office
                </p>

                <p>
                    <strong>Generated:</strong>
                    {{ now()->format('F d, Y h:i A') }}
                </p>

                <p>
                    <strong>Prepared By:</strong>
                    {{ auth()->user()->fullname ?? auth()->user()->name }}
                </p>

            </div>

        </div>

        <div>

            <button
                onclick="window.print()"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded shadow print:hidden">

                🖨 Print Report

            </button>

        </div>

    </div>


    {{-- =========================
        KPI CARDS
    ========================== --}}

    @yield('kpi-cards')


    {{-- =========================
        EXECUTIVE SUMMARY
    ========================== --}}

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">

        <h2 class="text-2xl font-bold text-blue-800 mb-4">

            Executive Assessment

        </h2>

        @yield('executive-summary')

    </div>


    {{-- =========================
        REPORT TABLE
    ========================== --}}

    <div class="bg-white rounded-lg shadow mb-8">

        <div class="border-b p-5">

            <h2 class="text-2xl font-bold">

                @yield('table-title')

            </h2>

        </div>

        @yield('report-table')

    </div>


    {{-- =========================
        RECOMMENDATIONS
    ========================== --}}

    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-6 mb-8">

        <h2 class="text-2xl font-bold text-yellow-800 mb-4">

            Recommendation

        </h2>

        @yield('recommendation')

    </div>


    {{-- =========================
        SIGNATURES
    ========================== --}}

    <div class="bg-white rounded-lg shadow p-8">

        <div class="grid grid-cols-3 gap-10 text-center">

            <div>

                <div class="border-b border-gray-500 h-12 mb-2"></div>

                <strong>Prepared By</strong>

                <p class="mt-2 text-sm text-gray-600">

                    {{ auth()->user()->fullname ?? auth()->user()->name }}

                </p>

            </div>

            <div>

                <div class="border-b border-gray-500 h-12 mb-2"></div>

                <strong>Reviewed By</strong>

            </div>

            <div>

                <div class="border-b border-gray-500 h-12 mb-2"></div>

                <strong>Approved By</strong>

            </div>

        </div>

    </div>

</div>

@endsection
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GenServis</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: linear-gradient(to right, #0f766e, #1e3a8a);
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-800">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-lg hidden md:flex flex-col">

            <!-- LOGO -->
            <div class="p-4 border-b flex items-center space-x-2">
                <img src="/images/logo.png" class="h-10 w-auto">
                <span class="font-bold text-lg">GenServis</span>
            </div>

            <!-- MENU -->
            <nav class="flex-1 p-4 space-y-2 text-sm">

                @if(Auth::user()->role === 'supervisor')

            <!-- Dashboard -->
            <a href="/supervisor/dashboard"
            class="block px-3 py-2 rounded
            {{ request()->is('supervisor/dashboard') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                🏠 Dashboard
            </a>

            @endif

            @if(Auth::user()->role === 'personnel')

            <!-- Dashboard (personal, role-tailored — kept at the top so it's
                 always reachable regardless of how many sections below it
                 apply to this account) -->
            <a href="{{ route('personnel.dashboard') }}"
            class="block px-3 py-2 rounded
            {{ request()->is('personnel/dashboard') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                🏠 Dashboard
            </a>

            <div class="border-t my-2"></div>

            @endif

            <!-- User Approval -->

            @if(auth()->user()->hasPermission('approve-users'))

                <a href="{{ route('admin.users.pending') }}"
                class="block px-3 py-2 rounded
                {{ request()->is('admin/users/pending') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                    👥 User Approval
                </a>

            @endif

            @if(auth()->user()->hasPermission('approve-leave-requests'))

            <!-- Leave Requests -->
            <a href="{{ route('leave.requests') }}"
            class="block px-3 py-2 rounded
            {{ request()->is('leave-requests') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                📄 Leave Requests
            </a>

            @endif

            @if(auth()->user()->hasPermission('approve-dtr'))

            <!-- DTR Approvals -->
            <a href="{{ route('utility-dtr.hr.pending') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('utility-dtr.hr.pending') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                🏁 DTR Approvals
            </a>

            @endif

            <!-- Divider -->
            <div class="border-t my-2"></div>

            <!-- INVENTORY MANAGEMENT -->

            @if(
                auth()->user()->hasPermission('view-materials') ||
                auth()->user()->hasPermission('process-material-requests') ||
                auth()->user()->hasPermission('create-walkin-requests') ||
                auth()->user()->hasPermission('view-walkin-requests') ||
                auth()->user()->hasPermission('view-material-logs') ||
                auth()->user()->hasPermission('view-department-inventory')
            )

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-4 mb-2">
                Inventory Management
            </div>

            @endif

            <!-- Materials Inventory -->

            @if(auth()->user()->hasPermission('view-materials'))

                <a href="{{ route('materials.index') }}"
                class="block px-3 py-2 rounded
                {{ request()->is('materials') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                    📦 Materials Inventory

                </a>

            @endif

            @if(auth()->user()->hasPermission('process-material-requests'))

            <!-- Material Requests -->

            <a href="/supervisor/material-requests"
            class="block px-3 py-2 rounded
            {{ request()->is('supervisor/material-requests*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                📋 Material Requests

            </a>

            @endif

            @if(auth()->user()->hasPermission('approve-job-requests-physical-plant')
                || auth()->user()->hasPermission('approve-job-requests-utility')
                || auth()->user()->hasPermission('view-utility-staff'))

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-4 mb-2">
                Job Request Management
            </div>

            @if(auth()->user()->hasPermission('approve-job-requests-physical-plant') || auth()->user()->hasPermission('approve-job-requests-utility'))

            <!-- Job Request Approvals -->

            <a href="{{ route('job-requests.index') }}"
            class="flex items-center justify-between px-3 py-2 rounded
            {{ request()->routeIs('job-requests.index') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                <span>🛠️ Job Request Approvals</span>

                @if($pendingJobRequestCount > 0)
                <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                    {{ $pendingJobRequestCount }}
                </span>
                @endif

            </a>

            <a href="{{ route('job-requests.reports') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('job-requests.reports*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                📊 Job Request Reports

            </a>

            @endif

            @if(auth()->user()->hasPermission('view-utility-staff'))

            <a href="{{ route('employees.utility-staff') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('employees.utility-staff') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                🧰 Utility & Maintenance Staff

            </a>

            @endif

            @endif

            @if(auth()->user()->hasPermission('manage-utility-schedule'))

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-4 mb-2">
                Utility Scheduling
            </div>

            <a href="{{ route('utility-schedule.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('utility-schedule.index') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                📅 Utility Schedule

            </a>

            <a href="{{ route('utility-schedule.attendance-report') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('utility-schedule.attendance-report*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                📊 Attendance Report

            </a>

            <a href="{{ route('utility-dtr.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('utility-dtr.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                🗓️ Monthly DTR

            </a>

            @endif

            @if(auth()->user()->hasPermission('manage-project-estimates'))

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-4 mb-2">
                Project Estimates
            </div>

            <a href="{{ route('project-estimates.index') }}"
            class="flex items-center justify-between px-3 py-2 rounded
            {{ request()->routeIs('project-estimates.index') || request()->routeIs('project-estimates.show') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                <span>🧾 Project Estimates</span>

                @if($myOngoingProjectEstimatesCount > 0)
                <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">
                    {{ $myOngoingProjectEstimatesCount }}
                </span>
                @endif

            </a>

            <a href="{{ route('project-estimates.reports') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('project-estimates.reports*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                📊 Estimate Report

            </a>

            @endif

            @if(auth()->user()->hasPermission('manage-building-inspections'))

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-4 mb-2">
                Building Inspections
            </div>

            <a href="{{ route('building-inspections.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('building-inspections.index') || request()->routeIs('building-inspections.show') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                🏢 Inspection Checklist

            </a>

            <a href="{{ route('building-inspections.reports') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('building-inspections.reports*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                📊 Inspection Report

            </a>

            @endif

            @if(auth()->user()->hasPermission('approve-utility-leave'))

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-4 mb-2">
                Utility Leave
            </div>

            <a href="{{ route('utility-leave.index') }}"
            class="flex items-center justify-between px-3 py-2 rounded
            {{ request()->routeIs('utility-leave.index') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                <span>📄 Leave Requests</span>

                @if($pendingUtilityLeaveCount > 0)
                <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                    {{ $pendingUtilityLeaveCount }}
                </span>
                @endif

            </a>

            <a href="{{ route('utility-leave.reports') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('utility-leave.reports*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                📊 Leave Report

            </a>

            @endif

            @if(auth()->user()->hasPermission('manage-property-inventory'))

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-4 mb-2">
                Room Inventory
            </div>

            <a href="{{ route('property-inventory.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('property-inventory.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                🏠 Room Inventory of Property

            </a>

            @if(auth()->user()->hasPermission('manage-property-issuance'))

            <a href="{{ route('property-issuances.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('property-issuances.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                🧾 Property Issuances (ICS/PAR)

            </a>

            @endif

            @endif

            @if(auth()->user()->hasPermission('manage-energy-reports') || auth()->user()->hasPermission('manage-water-bills'))

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-4 mb-2">
                Energy Conservation
            </div>

            @if(auth()->user()->hasPermission('manage-energy-reports'))

            <a href="{{ route('energy-reports.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('energy-reports.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                💡 Energy Conservation Report

            </a>

            @endif

            @if(auth()->user()->hasPermission('manage-water-bills'))

            <a href="{{ route('water-bills.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('water-bills.*') || request()->routeIs('water-meters.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                🚰 Water Bill Report

            </a>

            @endif

            @endif

            @if(auth()->user()->hasPermission('manage-health-consultations') || auth()->user()->hasPermission('manage-clinic-medicines'))

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-4 mb-2">
                Health Services
            </div>

            @if(auth()->user()->hasPermission('manage-health-consultations'))

            <a href="{{ route('health-consultations.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('health-consultations.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                🩺 Health Consultations

            </a>

            @endif

            @if(auth()->user()->hasPermission('manage-clinic-medicines'))

            <a href="{{ route('clinic-medicines.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('clinic-medicines.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                💊 Clinic Medicine Inventory

            </a>

            @endif

            @endif

            @if(auth()->user()->hasPermission('manage-admission-applicants'))

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-4 mb-2">
                Admission Testing
            </div>

            <a href="{{ route('admission-years.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('admission-years.*') || request()->routeIs('admission-applicants.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                🎓 Admission Years

            </a>

            <a href="{{ route('exam-sessions.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('exam-sessions.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                📝 Exam Results

            </a>

            <a href="{{ route('program-rankings.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('program-rankings.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                🏆 Program Rankings

            </a>

            <a href="{{ route('reapplications.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('reapplications.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                🔄 Reapplications

            </a>

            <a href="{{ route('final-admissions.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('final-admissions.*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                📋 Final List of Admission

            </a>

            @endif

            <!-- Walk-In Issuance -->

            @if(auth()->user()->hasPermission('create-walkin-requests'))

                <a href="{{ route('walkin.create') }}"
                class="block px-3 py-2 rounded
                {{ request()->routeIs('walkin.create') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                    🚶 Walk-In Issuance

                </a>

            @endif

            @if(auth()->user()->hasPermission('view-walkin-requests'))

                <a href="{{ route('walkin.history') }}"
                class="block px-3 py-2 rounded
                {{ request()->routeIs('walkin.history') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                    📋 Walk-In History

                </a>

            @endif

            <!-- Material Logs -->

            @if(auth()->user()->hasPermission('view-material-logs'))

                <a href="{{ route('materials.logs') }}"
                class="block px-3 py-2 rounded
                {{ request()->is('materials/logs*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                    📜 Material Logs

                </a>

            @endif

            <!-- Department Inventory -->

            @if(auth()->user()->hasPermission('view-department-inventory'))

                <a href="{{ route('department.inventory') }}"
                class="block px-3 py-2 rounded
                {{ request()->is('department-inventory') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                    🏢 Department Inventory

                </a>


                <a href="{{ route('department.inventory.balance') }}"
                class="block px-3 py-2 rounded
                {{ request()->is('department-inventory-balance') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                    📦 Department Balance

                </a>

            @endif


            <!-- Divider -->
            <div class="border-t my-2"></div>

            <!-- INVENTORY SETTINGS -->

            @if(
                auth()->user()->hasPermission('create-materials') ||
                auth()->user()->hasPermission('view-categories') ||
                auth()->user()->hasPermission('view-units') ||
                auth()->user()->hasPermission('view-departments') ||
                auth()->user()->hasPermission('view-inventory-movements')
            )

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-6 mb-2">
                Inventory Settings
            </div>

            @endif

            <!-- Add Material -->

            @if(auth()->user()->hasPermission('create-materials'))

                <a href="{{ route('materials.create') }}"
                class="block px-3 py-2 rounded
                {{ request()->is('materials/create') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                    ➕ Add Material

                </a>

            @endif

            <!-- Categories -->

            @if(auth()->user()->hasPermission('view-categories'))

                <a href="{{ route('categories.index') }}"
                class="block px-3 py-2 rounded
                {{ request()->is('categories*')
                    ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg'
                    : 'hover:bg-green-100' }}">

                    🗂️ Categories

                </a>

            @endif

            <!-- Units -->

            @if(auth()->user()->hasPermission('view-units'))

                <a href="{{ route('units.index') }}"
                class="block px-3 py-2 rounded
                {{ request()->is('units*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                    📏 Units

                </a>

            @endif

            <!-- Departments -->

            @if(auth()->user()->hasPermission('view-departments'))

                <a href="{{ route('supervisor.departments.index') }}"
                class="block px-3 py-2 rounded
                {{ request()->is('supervisor/departments*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                    🏢 Departments

                </a>

            @endif

            <!-- Inventory Movements -->

            @if(auth()->user()->hasPermission('view-inventory-movements'))

                <a href="{{ route('supervisor.inventory.movements.index') }}"
                class="block px-3 py-2 rounded
                {{ request()->is('supervisor/inventory-movements*') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                    🔄 Inventory Movements

                </a>

            @endif

            <!-- Divider -->
            <div class="border-t my-2"></div>

            <!-- PROCUREMENT PLANNING -->

            @if(auth()->user()->hasPermission('view-ppmp'))

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-6 mb-2">
                Procurement Planning
            </div>

            <!-- Dashboard -->

            <a href="{{ route('procurement.dashboard') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('procurement.dashboard') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                🏠 Dashboard

            </a>

            <!-- Annual PPMP -->

            <a href="{{ route('procurement.plans.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('procurement.plans.*')
                ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg'
                : 'hover:bg-green-100' }}">

                📄 Annual PPMP

            </a>

            <!-- Budget Monitoring -->

            @if(auth()->user()->hasPermission('view-budget-monitoring'))

            <a href="{{ route('procurement.budget-monitoring') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('procurement.budget-monitoring*')
                ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg'
                : 'hover:bg-green-100' }}">

                💰 Budget Monitoring

            </a>

            @endif

            <!-- Purchase Forecast -->

            @if(auth()->user()->hasPermission('view-purchase-forecast'))

            <a href="{{ route('procurement.purchase-forecast') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('procurement.purchase-forecast*')
                ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg'
                : 'hover:bg-green-100' }}">

                📈 Purchase Forecast

            </a>

            @endif

            <!-- Procurement Calendar -->

            @if(auth()->user()->hasPermission('view-procurement-calendar'))

            <a href="{{ route('procurement.calendar') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('procurement.calendar*')
                ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg'
                : 'hover:bg-green-100' }}">

                📅 Procurement Calendar

            </a>

            @endif

            @elseif(auth()->user()->hasPermission('manage-own-department-ppmp-items'))

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-6 mb-2">
                Procurement Planning
            </div>

            <a href="{{ route('procurement.plans.index') }}"
            class="block px-3 py-2 rounded
            {{ request()->routeIs('procurement.plans.*')
                ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg'
                : 'hover:bg-green-100' }}">

                📄 My Department PPMP

            </a>

            @endif

            <!-- REPORTS -->

            @if(auth()->user()->hasPermission('view-reports'))

                <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-6 mb-2">
                    Reports
                </div>

                <a href="{{ route('reports.index') }}"
                class="block px-3 py-2 rounded
                {{ request()->is('reports*')
                    ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg'
                    : 'hover:bg-green-100' }}">

                    📊 Reports Center

                </a>

            @endif

        <!-- ===================================================== -->
        <!-- SYSTEM ADMINISTRATION -->
        <!-- ===================================================== -->

        @if(auth()->user()->hasPermission('manage-roles')
            || auth()->user()->hasPermission('view-employees')
            || auth()->user()->hasPermission('view-user-access')
            || auth()->user()->hasPermission('reset-user-passwords')
            || auth()->user()->hasPermission('manage-permissions')
            || auth()->user()->hasPermission('view-activity-logs')
            || auth()->user()->hasPermission('manage-system-settings')
            || auth()->user()->role === 'supervisor')

        <div class="mt-8 mb-2 px-3">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                System Administration
            </p>
        </div>

        @endif

        @if(auth()->user()->hasPermission('manage-roles'))

        <a href="{{ route('roles.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg transition
        {{ request()->routeIs('roles.*') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' }}">

            <span>🔐</span>
            <span>Role Management</span>

        </a>

        @endif

        @if(auth()->user()->hasPermission('view-employees'))

            <a href="{{ route('employees.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg transition
            {{ request()->routeIs('employees.*')
                ? 'bg-green-100 text-green-700 font-semibold'
                : 'hover:bg-gray-100 text-gray-700' }}">

                <span>👥</span>
                <span>Employee Master</span>

            </a>

        @endif

        @if(auth()->user()->hasPermission('view-user-access'))

        <a href="{{ route('admin.user-access.index') }}"

        class="flex items-center gap-3 px-3 py-2 rounded-lg transition
        {{ request()->routeIs('admin.user-access.*')
            ? 'bg-green-100 text-green-700 font-semibold'
            : 'hover:bg-gray-100 text-gray-700' }}">

            <span>👤</span>
            <span>User Access</span>

        </a>

        @endif

        @if(auth()->user()->hasPermission('reset-user-passwords'))

        <a href="{{ route('admin.reset-password.index') }}"

        class="flex items-center gap-3 px-3 py-2 rounded-lg transition
        {{ request()->routeIs('admin.reset-password.*')
            ? 'bg-green-100 text-green-700 font-semibold'
            : 'hover:bg-gray-100 text-gray-700' }}">

            <span>🔑</span>
            <span>Reset Password</span>

        </a>

        @endif

        @if(auth()->user()->role === 'supervisor')

        <a href="{{ route('admin.reset-password.logs') }}"

        class="flex items-center gap-3 px-3 py-2 rounded-lg transition
        {{ request()->routeIs('admin.reset-password.logs')
            ? 'bg-green-100 text-green-700 font-semibold'
            : 'hover:bg-gray-100 text-gray-700' }}">

            <span>🗝️</span>
            <span>Password Reset Logs</span>

        </a>

        @endif

        @if(auth()->user()->hasPermission('manage-permissions'))

        <a href="{{ route('permissions.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg transition
        {{ request()->routeIs('permissions.*')
            ? 'bg-green-100 text-green-700 font-semibold'
            : 'hover:bg-gray-100 text-gray-700' }}">

            <span>🛡️</span>
            <span>Permission Management</span>

        </a>

        @endif

        @if(auth()->user()->hasPermission('view-activity-logs'))

        <a href="{{ route('admin.activity-logs.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg transition
        {{ request()->routeIs('admin.activity-logs.*')
            ? 'bg-green-100 text-green-700 font-semibold'
            : 'hover:bg-gray-100 text-gray-700' }}">

            <span>📜</span>
            <span>Activity Logs</span>

        </a>

        @endif

        @if(auth()->user()->hasPermission('manage-system-settings'))

        <a href="{{ route('admin.system-settings.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg transition
        {{ request()->routeIs('admin.system-settings.*')
            ? 'bg-green-100 text-green-700 font-semibold'
            : 'hover:bg-gray-100 text-gray-700' }}">

            <span>⚙️</span>
            <span>System Settings</span>

        </a>

        @endif


                <!-- PERSONNEL -->
                {{-- Hidden entirely for users who already have real inventory-management
                     access (e.g. Inventory Custodian) — the self-service personnel/leave/
                     material-request flows don't apply to their designation. --}}
                @php
                    $isSelfServicePersonnel = Auth::user()->role === 'personnel' && !auth()->user()->hasPermission('view-materials');
                    $isUtilityStaffMember = Auth::user()->personnel
                        && \App\Models\Personnel::utilityStaff()->where('id', Auth::user()->personnel->id)->exists();
                @endphp

                @if($isSelfServicePersonnel)

                    <div class="text-xs font-bold text-gray-400 uppercase px-3 mb-2">
                        Inventory Services
                    </div>

                    <a href="/material-request"
                    class="block px-4 py-3 rounded-xl transition
                    {{ request()->is('material-request') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                        📦 Material Request
                    </a>

                    <a href="{{ route('material-request.history') }}"
                    class="block px-4 py-3 rounded-xl transition
                    {{ request()->is('material-request/history') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                        📜 Request History
                    </a>

                @endif

                @if(in_array(Auth::user()->role, ['personnel', 'supervisor']))

                    <div class="text-xs font-bold text-gray-400 uppercase px-3 mb-2 mt-3">
                        Job Request Services
                    </div>

                    <a href="{{ route('job-requests.create') }}"
                    class="block px-4 py-3 rounded-xl transition
                    {{ request()->routeIs('job-requests.create') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                        🛠️ Job Request
                    </a>

                    <a href="{{ route('job-requests.history') }}"
                    class="flex items-center justify-between px-4 py-3 rounded-xl transition
                    {{ request()->routeIs('job-requests.history') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                        <span>📜 My Job Requests</span>

                        @if($myJobRequestsInProgressCount > 0)
                        <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">
                            {{ $myJobRequestsInProgressCount }}
                        </span>
                        @endif

                    </a>

                    <a href="{{ route('job-requests.my-assigned') }}"
                    class="flex items-center justify-between px-4 py-3 rounded-xl transition
                    {{ request()->routeIs('job-requests.my-assigned') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">

                        <span>🔧 My Assigned Jobs</span>

                        @if($myAssignedJobsPendingCount > 0)
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                            {{ $myAssignedJobsPendingCount }}
                        </span>
                        @endif

                    </a>

                @endif

                @if(in_array(Auth::user()->role, ['personnel', 'supervisor']))

                    <div class="border-t my-2"></div>

                    <div class="text-xs font-bold text-gray-400 uppercase px-3 mb-2">
                        Property Services
                    </div>

                    <a href="{{ route('property-issuances.mine') }}"
                    class="block px-4 py-3 rounded-xl transition
                    {{ request()->routeIs('property-issuances.mine') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                        🧾 My Property Accountability
                    </a>

                @endif

                {{-- Utility & Maintenance Staff only (e.g. Rony, Aldrin) —
                     not every personnel/supervisor account, since a
                     duty roster only applies to this specific pool. --}}
                @if($isUtilityStaffMember)

                    <div class="text-xs font-bold text-gray-400 uppercase px-3 mb-2 mt-3">
                        Utility Scheduling
                    </div>

                    <a href="{{ route('utility-schedule.my') }}"
                    class="block px-4 py-3 rounded-xl transition
                    {{ request()->routeIs('utility-schedule.my') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                        📅 My Schedule
                    </a>

                    <a href="{{ route('utility-dtr.my') }}"
                    class="block px-4 py-3 rounded-xl transition
                    {{ request()->routeIs('utility-dtr.my') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                        🗓️ My DTR
                    </a>

                    <div class="text-xs font-bold text-gray-400 uppercase px-3 mb-2 mt-3">
                        Leave Services
                    </div>

                    <a href="{{ route('leave.index') }}"
                    class="block px-4 py-3 rounded-xl transition
                    {{ request()->routeIs('leave.index') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                        📝 Apply Leave
                    </a>

                    <a href="{{ route('leave.history') }}"
                    class="block px-4 py-3 rounded-xl transition
                    {{ request()->routeIs('leave.history') ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg' : 'hover:bg-green-100' }}">
                        📄 Leave History
                    </a>

                @endif

            </nav>

        </aside>


        <!-- MAIN -->
        <div class="flex-1 flex flex-col">

            <!-- TOP NAVBAR -->
            <nav class="bg-white shadow px-6 py-3 flex justify-between items-center">

                <span class="font-semibold text-lg">
                    {{ Auth::user()->role === 'supervisor' ? 'Supervisor Panel' : 'Personnel Panel' }}
                </span>

                <div class="flex items-center space-x-4">

                    <!-- NOTIFICATION -->
                    <div class="relative">
                        <button onclick="toggleNotif()" class="text-xl focus:outline-none">
                            🔔
                        </button>

                        @if($totalNotifCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1 rounded-full">
                            {{ $totalNotifCount }}
                        </span>
                        @endif

                        <!-- DROPDOWN -->
                        <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border text-sm z-40">
                            <div class="p-3 border-b font-semibold">Notifications</div>

                            <div class="max-h-64 overflow-y-auto">

                                @forelse($notifications as $notif)

                                <form method="POST"
                                    action="{{ route('notifications.read', $notif->id) }}">
                                    @csrf

                                   <button
                                    type="submit"
                                    class="w-full text-left px-4 py-3 border-b hover:bg-gray-100">
                                    
                                        <div class="text-sm font-semibold">
                                            {{ $notif->title }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            {{ $notif->message }}
                                        </div>

                                        <div class="text-[10px] text-gray-400">
                                            {{ $notif->created_at->diffForHumans() }}
                                        </div>

                                    </button>
                                </form>

                            @empty
                                    <div class="p-3 text-gray-500 text-sm">
                                        No new notifications
                                    </div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                    <!-- USER -->
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-sm font-medium hover:opacity-80">

                        @php $myPhotoUrl = Auth::user()->personnel?->photo_url; @endphp

                        @if($myPhotoUrl)
                            <img src="{{ $myPhotoUrl }}" alt="Profile" class="w-8 h-8 rounded-full object-cover border">
                        @else
                            <span class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->fullname ?? Auth::user()->username, 0, 1)) }}
                            </span>
                        @endif

                        <span>
                            {{ Auth::user()->fullname ?? Auth::user()->username }}

                            @if(Auth::user()->systemRole)
                            <span class="text-xs text-gray-500 font-normal">
                                ({{ Auth::user()->systemRole->name }}@if(Auth::user()->additionalRoles->isNotEmpty()) <span
                                    class="underline decoration-dotted cursor-help"
                                    title="{{ Auth::user()->additionalRoles->pluck('name')->join(', ') }}"
                                >+{{ Auth::user()->additionalRoles->count() }} more</span>@endif)
                            </span>
                            @endif
                        </span>

                    </a>

                    <!-- LOGOUT -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                            Logout
                        </button>
                    </form>

                </div>

            </nav>


            <!-- HEADER -->
            @isset($header)
                <div class="p-6">
                    {{ $header }}
                </div>
            @endisset


            <!-- CONTENT -->
            <main class="p-6">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

        </div>

    </div>

    <!-- CONFIRM MODAL -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 transition-opacity duration-200">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-96 text-center scale-95 transition-transform duration-200">

        <h2 class="text-lg font-bold mb-4">Confirm Action</h2>
        <p id="confirmMessage" class="text-sm text-gray-600 mb-6">
            Are you sure?
        </p>

        <div class="flex justify-center gap-4">
            <button onclick="closeModal()"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Cancel
            </button>

            <button id="confirmBtn"
                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                Confirm
            </button>
        </div>
    </div>
</div>



<script>
    let selectedForm = null;

    function openModal(form, message = "Are you sure?") {
        selectedForm = form;

        const modal = document.getElementById('confirmModal');
        const messageEl = document.getElementById('confirmMessage');
        const confirmBtn = document.getElementById('confirmBtn');

        messageEl.innerText = message;

        if (message.toLowerCase().includes('approve')) {
            confirmBtn.className = "px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700";
        } else {
            confirmBtn.className = "px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600";
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        selectedForm = null;
    }

    function toggleNotif() {
        const dropdown = document.getElementById('notifDropdown');
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('confirmBtn').addEventListener('click', function () {
            if (selectedForm) selectedForm.submit();
        });
    });

    document.addEventListener('click', function (e) {
    const dropdown = document.getElementById('notifDropdown');
    const button = e.target.closest('button');

    if (!e.target.closest('#notifDropdown') && !button) {
            dropdown.classList.add('hidden');
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
</body>
</html>
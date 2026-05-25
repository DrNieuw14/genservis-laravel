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
            {{ request()->is('supervisor/dashboard') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">
                🏠 Dashboard
            </a>

            <!-- User Approval -->
            <a href="{{ route('admin.users.pending') }}"
            class="block px-3 py-2 rounded 
            {{ request()->is('admin/users/pending') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">
                👥 User Approval
            </a>

            <!-- Leave Requests -->
            <a href="{{ route('leave.requests') }}"
            class="block px-3 py-2 rounded 
            {{ request()->is('leave-requests') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">
                📄 Leave Requests
            </a>

            <!-- Divider -->
            <div class="border-t my-2"></div>

            <!-- INVENTORY MANAGEMENT -->

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-4 mb-2">
                Inventory Management
            </div>

            <!-- Materials Inventory -->

            <a href="{{ route('materials.index') }}"
            class="block px-3 py-2 rounded 
            {{ request()->is('materials') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">

                📦 Materials Inventory

            </a>

            <!-- Material Requests -->

            <a href="/supervisor/material-requests"
            class="block px-3 py-2 rounded 
            {{ request()->is('supervisor/material-requests*') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">

                📋 Material Requests

            </a>

            <!-- Material Logs -->

            <a href="{{ route('materials.logs') }}"
            class="block px-3 py-2 rounded 
            {{ request()->is('materials/logs*') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">

                📜 Material Logs

            </a>


            <!-- INVENTORY SETTINGS -->

            <div class="text-xs font-bold text-gray-400 uppercase px-3 mt-6 mb-2">
                Inventory Settings
            </div>

            <!-- Add Material -->

            <a href="{{ route('materials.create') }}"
            class="block px-3 py-2 rounded 
            {{ request()->is('materials/create') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">

                ➕ Add Material

            </a>

            <!-- Categories -->

            <a href="{{ route('categories.index') }}"
            class="block px-3 py-2 rounded 
            {{ request()->is('categories*') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">

                🗂️ Categories

            </a>

            <!-- Units -->

            <a href="{{ route('units.index') }}"
            class="block px-3 py-2 rounded 
            {{ request()->is('units*') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">

                📏 Units

            </a>

            <!-- Departments -->

            <a href="{{ route('supervisor.departments.index') }}"
            class="block px-3 py-2 rounded 
            {{ request()->is('supervisor/departments*') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">

                🏢 Departments

            </a>

        @endif


                <!-- PERSONNEL -->
                @if(Auth::user()->role === 'personnel')

                    <a href="/personnel/dashboard"
                    class="block px-3 py-2 rounded 
                    {{ request()->is('personnel/dashboard') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">
                        Dashboard
                    </a>

                    <a href="/attendance"
                    class="block px-3 py-2 rounded hover:bg-green-100">
                        Attendance
                    </a>

                    <a href="/schedule"
                    class="block px-3 py-2 rounded hover:bg-green-100">
                        Scheduling
                    </a>

                    <a href="/leave"
                    class="block px-3 py-2 rounded hover:bg-green-100">
                        Leave
                    </a>

                    <a href="/material-request" class="block px-4 py-2 hover:bg-green-200">
                        📦 Material Request
                    </a>

                    <a href="{{ route('material-request.history') }}"
                    class="block px-4 py-2 hover:bg-green-200">
                        📜 Request History
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

                                <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                    @csrf

                                    <button type="submit"
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
                    <span class="text-sm font-medium">
                        {{ Auth::user()->fullname ?? Auth::user()->username }}
                    </span>

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
</body>
</html>
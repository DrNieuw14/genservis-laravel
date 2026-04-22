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

                <!-- SUPERVISOR -->
                @if(Auth::user()->role === 'supervisor')

                    <a href="/supervisor/dashboard"
                    class="block px-3 py-2 rounded 
                    {{ request()->is('supervisor/dashboard') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">
                        Dashboard
                    </a>

                    <a href="/supervisor/approve-users"
                    class="block px-3 py-2 rounded 
                    {{ request()->is('supervisor/approve-users') ? 'bg-green-200 font-semibold' : 'hover:bg-green-100' }}">
                        Approve Users
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
                        🔔
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1 rounded-full">
                            0
                        </span>
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
                {{ $slot }}
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
        document.getElementById('confirmMessage').innerText = message;
        document.getElementById('confirmModal').classList.remove('hidden');
        document.getElementById('confirmModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        document.getElementById('confirmModal').classList.remove('flex');
        selectedForm = null;
    }

    document.getElementById('confirmBtn').addEventListener('click', function () {
        if (selectedForm) selectedForm.submit();
    });
</script>

<script>
    let selectedForm = null;

    function openModal(form, message = "Are you sure?") {
        selectedForm = form;

        const modal = document.getElementById('confirmModal');
        const messageEl = document.getElementById('confirmMessage');
        const confirmBtn = document.getElementById('confirmBtn');

        messageEl.innerText = message;

        // ✅ CHANGE BUTTON COLOR BASED ON ACTION
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

    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('confirmBtn').addEventListener('click', function () {
            if (selectedForm) selectedForm.submit();
        });
    });
</script>
</body>
</html>
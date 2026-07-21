<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GenServis</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .hero-bg {
            background: url('/images/bg.jpg') no-repeat center center;
            background-size: cover;
        }

        .overlay {
            background: rgba(15, 118, 110, 0.75); /* green overlay */
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- NAVBAR -->
    <nav class="bg-white/90 backdrop-blur-md shadow-md fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">

            <!-- LEFT -->
            <div class="flex items-center space-x-3">
                <img src="/images/logo.png" alt="Logo" class="h-10 w-auto object-contain">
                <h1 class="text-lg font-bold text-gray-800">
                    GenServis
                </h1>
            </div>

            <!-- RIGHT -->
            <div class="space-x-4">
                <a href="{{ route('login') }}"
                   class="text-sm font-medium text-gray-700 hover:text-blue-600">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                    Register
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-bg min-h-screen flex items-center justify-center relative">

        <!-- OVERLAY -->
        <div class="overlay absolute inset-0"></div>

        <!-- CONTENT -->
        <div class="relative z-10 text-center text-white px-6 max-w-3xl">

            <!-- LOGO -->
            <div class="flex justify-center mb-6">
                <img src="/images/logo.png" class="h-24 w-auto object-contain drop-shadow-lg">
            </div>

            <!-- TITLE -->
            <h1 class="text-5xl font-extrabold mb-4">
                GenServis
            </h1>

            <!-- SUBTITLE -->
                <p class="text-lg text-gray-200">
                    Cavite State University 
                <p class="text-base text-green-100 mt-1">
                    Carmona Campus
                </p>

                <!-- Divider -->
                <div class="w-24 h-1 bg-yellow-400 rounded-full mx-auto my-6"></div>

                <!-- ASCEND -->
                <p class="text-white text-lg font-semibold">
                    ASCEND
                </p>

                <p class="text-gray-200 leading-relaxed mt-2">
                    Advancing Sustained Change and Excellence for National Development
                </p>

                <p class="italic text-yellow-300 font-medium mt-3 mb-8">
                    "ASCEND with Truth, Integrity, Excellence, and Service."
                </p>

                <!-- BUTTONS -->
                <div class="flex justify-center gap-4">

                <a href="{{ route('login') }}"
                   class="px-6 py-3 bg-white text-green-700 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                    Register
                </a>

            </div>

            <div class="mt-6">
                <a href="{{ route('attendance-kiosk.index') }}"
                   class="inline-block px-6 py-3 bg-yellow-400 text-green-900 font-semibold rounded-lg shadow hover:bg-yellow-300 transition">
                    📷 Utility Attendance Scan
                </a>
            </div>

        </div>

    </section>

</body>
</html>
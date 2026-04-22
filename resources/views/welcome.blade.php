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
            <p class="text-lg mb-8 text-gray-200">
                Cavite State University – General Services Management System for Personnel, Scheduling, and Leave Monitoring
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

        </div>

    </section>

</body>
</html>
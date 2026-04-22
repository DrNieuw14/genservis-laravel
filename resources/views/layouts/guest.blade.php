<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GenServis</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: url('/images/bg.jpg') no-repeat center center;
            background-size: cover;
        }

        .overlay {
            background: rgba(15, 118, 110, 0.75);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative">

    <!-- OVERLAY -->
    <div class="overlay absolute inset-0"></div>

    <!-- MAIN CONTAINER -->
    <div class="relative z-10 w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        <!-- LEFT SIDE -->
        <div class="bg-green-700 text-white p-10 flex flex-col justify-center items-center text-center">

            <img src="/images/logo.png" class="h-24 w-auto object-contain mb-6">

            <h2 class="text-xl font-bold">Cavite State University</h2>
            <p class="text-sm mt-2">GenServis System</p>

            <p class="text-xs mt-4 text-green-100">
                Efficient service, scheduling, and leave management system.
            </p>

        </div>

        <!-- RIGHT SIDE (FORM SLOT) -->
        <div class="p-10">
            {{ $slot }}
        </div>

    </div>

</body>
</html>
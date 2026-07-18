@extends('layouts.app')

@section('content')

@php
    // Full literal Tailwind class strings — kept here (not built dynamically
    // in the controller) because Tailwind's content scanner only reads
    // .blade.php files, not app/**/*.php, so a runtime-concatenated class
    // string would silently never make it into the compiled CSS.
    $colorMap = [
        'blue'   => ['border' => 'border-l-blue-500',   'text' => 'text-blue-600'],
        'purple' => ['border' => 'border-l-purple-500', 'text' => 'text-purple-600'],
        'green'  => ['border' => 'border-l-green-500',  'text' => 'text-green-600'],
        'orange' => ['border' => 'border-l-orange-500', 'text' => 'text-orange-600'],
        'red'    => ['border' => 'border-l-red-500',    'text' => 'text-red-600'],
        'yellow' => ['border' => 'border-l-yellow-500', 'text' => 'text-yellow-600'],
    ];

    $badgeColorMap = [
        'red'    => 'text-red-600',
        'yellow' => 'text-yellow-600',
        'green'  => 'text-green-600',
        'gray'   => 'text-gray-400',
    ];
@endphp

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- PAGE HEADER -->
    <div class="mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            🏠 Personnel Dashboard
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            @if(count($cards) > 0)
                Quick access to your most-used tools.
            @else
                Quick access to attendance, scheduling, and leave services.
            @endif
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        @forelse($cards as $card)

            <a href="{{ $card['href'] }}" class="block h-full">
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow hover:shadow-lg transition border-l-4 {{ $colorMap[$card['color']]['border'] }} cursor-pointer h-full">
                    <h3 class="font-bold {{ $colorMap[$card['color']]['text'] }}">
                        {{ $card['icon'] }} {{ $card['title'] }}
                    </h3>
                    <p class="text-sm text-gray-500">{{ $card['subtitle'] }}</p>

                    @if(!empty($card['badge']))
                        <p class="text-xs {{ $badgeColorMap[$card['badgeColor']] ?? 'text-gray-400' }} font-semibold mt-2">
                            {{ $card['badge'] }}
                        </p>
                    @endif
                </div>
            </a>

        @empty

            <!-- Attendance -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow hover:shadow-lg transition border-l-4 border-l-blue-500 cursor-pointer">
                <h3 class="font-bold text-blue-600">Attendance</h3>
                <p class="text-sm text-gray-500">Manage attendance records</p>
            </div>

            <!-- Scheduling -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow hover:shadow-lg transition border-l-4 border-l-green-500 cursor-pointer">
                <h3 class="font-bold text-green-600">Scheduling</h3>
                <p class="text-sm text-gray-500">Manage schedules</p>
            </div>

            <!-- Leave -->
            <a href="{{ url('/leave') }}" class="block h-full">
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow hover:shadow-lg transition border-l-4 border-l-yellow-500 cursor-pointer h-full">
                    <h3 class="font-bold text-yellow-600">Leave</h3>
                    <p class="text-sm text-gray-500">Apply leave requests</p>
                </div>
            </a>

            <!-- CTO -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow hover:shadow-lg transition border-l-4 border-l-red-500 cursor-pointer">
                <h3 class="font-bold text-red-600">CTO</h3>
                <p class="text-sm text-gray-500">Manage CTO balances</p>
            </div>

        @endforelse

    </div>

</div>

@endsection

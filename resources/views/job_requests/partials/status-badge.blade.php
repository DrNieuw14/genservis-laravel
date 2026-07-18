@php
    $badges = [
        'pending' => ['⏳ Pending', 'bg-yellow-400 text-black'],
        'approved' => ['✅ Approved', 'bg-blue-500 text-white'],
        'assigned' => ['👷 Assigned', 'bg-purple-600 text-white'],
        'work_done' => ['🔧 Work Done — Awaiting Sign-Off', 'bg-teal-600 text-white'],
        'completed' => ['🏁 Completed', 'bg-green-600 text-white'],
        'rejected' => ['❌ Rejected', 'bg-red-600 text-white'],
        'cancelled' => ['🚫 Cancelled', 'bg-gray-500 text-white'],
    ];

    [$label, $classes] = $badges[$status] ?? [$status, 'bg-gray-400 text-white'];
@endphp

<span class="{{ $classes }} px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap">
    {{ $label }}
</span>

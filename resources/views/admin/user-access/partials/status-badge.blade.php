@php
    $statusStyles = [
        'approved' => ['bg-green-100 text-green-700', 'Active'],
        'pending' => ['bg-yellow-100 text-yellow-700', 'Pending'],
        'suspended' => ['bg-orange-100 text-orange-700', 'Suspended'],
        'locked' => ['bg-red-100 text-red-700', 'Locked'],
        'inactive' => ['bg-gray-100 text-gray-600', 'Inactive'],
        'rejected' => ['bg-red-100 text-red-700', 'Rejected'],
    ];

    [$statusClasses, $statusLabel] = $statusStyles[$status] ?? ['bg-gray-100 text-gray-600', ucfirst($status)];
@endphp

<span class="px-3 py-1 rounded-full {{ $statusClasses }} text-xs font-semibold">
    {{ $statusLabel }}
</span>

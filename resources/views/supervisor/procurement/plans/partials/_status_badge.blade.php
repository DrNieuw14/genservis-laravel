@php
    $statusColors = [
        'Draft' => 'bg-yellow-100 text-yellow-700',
        'Submitted' => 'bg-blue-100 text-blue-700',
        'Reviewed' => 'bg-indigo-100 text-indigo-700',
        'Approved' => 'bg-green-100 text-green-700',
        'Rejected' => 'bg-red-100 text-red-700',
        'Archived' => 'bg-gray-100 text-gray-700',
    ];

    $badgeClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';

    $sizeClass = ($size ?? 'sm') === 'lg'
        ? 'px-4 py-2 rounded-full'
        : 'px-2 py-1 rounded';
@endphp

<span class="{{ $badgeClass }} {{ $sizeClass }}">
    {{ $status }}
</span>

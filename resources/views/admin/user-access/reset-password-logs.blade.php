@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🗝️ Password Reset Logs
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Every password reset performed across the system, including by other administrators.
                Visible to the super admin account only.
            </p>

        </div>

        <x-back-button :href="route('admin.reset-password.index')" />

    </div>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Date &amp; Time</th>
                    <th class="p-3 text-left">Reset By</th>
                    <th class="p-3 text-left">Account Reset</th>
                    <th class="p-3 text-left">IP Address</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($logs as $log)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3">
                            {{ $log->created_at->format('M d, Y h:i A') }}
                        </td>

                        <td class="p-3">
                            {{ optional($log->user)->name ?? optional($log->user)->username ?? 'Unknown' }}
                            <span class="text-gray-500">
                                ({{ optional($log->user)->username ?? '-' }})
                            </span>
                        </td>

                        <td class="p-3">
                            @if($log->targetUser)
                                {{ optional($log->targetUser->personnel)->fullname ?? $log->targetUser->name }}
                                <span class="text-gray-500">
                                    ({{ $log->targetUser->username }})
                                </span>
                            @else
                                <span class="text-gray-400">Account deleted</span>
                            @endif
                        </td>

                        <td class="p-3 text-gray-500">
                            {{ $log->ip_address ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500">
                            No password resets on record yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>

</div>

@endsection

<div class="bg-white rounded-xl shadow mt-6">

    <div class="border-b px-6 py-4">
        <h2 class="text-xl font-semibold text-gray-800">
            📜 Change History
        </h2>

        <p class="text-gray-500 text-sm mt-1">
            Every edit made to this employee's account (position, department, employment status, role) by HR/admin staff.
        </p>
    </div>

    <div class="p-6">

        @if($history->isNotEmpty())

            <div class="space-y-3">

                @foreach($history as $entry)

                    <div class="border rounded-lg p-4">

                        <div class="flex justify-between items-start gap-4">

                            <div>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">
                                    {{ $entry->action }}
                                </span>

                                <p class="text-gray-700 mt-2">
                                    {{ $entry->description }}
                                </p>
                            </div>

                            <div class="text-right text-xs text-gray-500 whitespace-nowrap">
                                <div>{{ $entry->created_at->format('M d, Y g:i A') }}</div>
                                <div>by {{ optional($entry->user)->fullname ?? optional($entry->user)->name ?? 'System' }}</div>
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <p class="text-gray-500 text-sm">No changes recorded yet for this employee.</p>

        @endif

    </div>

</div>

<div class="bg-white rounded-xl shadow mt-6">

    <div class="border-b px-6 py-4">
        <h2 class="text-xl font-semibold text-gray-800">
            🛠️ Actions Performed by This Account
        </h2>

        <p class="text-gray-500 text-sm mt-1">
            Edits this person has made to other employees' records (relevant for HR/admin accounts).
        </p>
    </div>

    <div class="p-6">

        @if($performedActions->isNotEmpty())

            <div class="space-y-3">

                @foreach($performedActions as $entry)

                    <div class="border rounded-lg p-4">

                        <div class="flex justify-between items-start gap-4">

                            <div>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">
                                    {{ $entry->action }}
                                </span>

                                <p class="text-gray-700 mt-2">
                                    {{ $entry->description }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    On: {{ optional(optional($entry->targetUser)->personnel)->fullname ?? optional($entry->targetUser)->name ?? 'Unknown' }}
                                </p>
                            </div>

                            <div class="text-right text-xs text-gray-500 whitespace-nowrap">
                                {{ $entry->created_at->format('M d, Y g:i A') }}
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <p class="text-gray-500 text-sm">This account hasn't made any recorded changes to other accounts yet.</p>

        @endif

    </div>

</div>

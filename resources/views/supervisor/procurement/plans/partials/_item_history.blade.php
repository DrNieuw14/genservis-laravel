<!-- Item History -->

<div class="bg-white rounded-xl shadow mt-6">

    <div class="p-6 border-b">

        <h2 class="text-xl font-bold">

            Item History

        </h2>

        <p class="text-gray-500">

            A record of items edited or removed from this plan, and why.

        </p>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Action</th>
                    <th class="px-4 py-3 text-left">Material</th>
                    <th class="px-4 py-3 text-left">Reason</th>
                    <th class="px-4 py-3 text-left">By</th>
                </tr>
            </thead>

            <tbody>

                @forelse($plan->itemLogs as $log)

                    <tr class="border-b hover:bg-gray-50">

                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $log->created_at->format('M d, Y h:i A') }}
                        </td>

                        <td class="px-4 py-3">

                            @if($log->action === 'deleted')
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-sm">Deleted</span>
                            @else
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">Edited</span>
                            @endif

                        </td>

                        <td class="px-4 py-3">
                            {{ $log->material_name }}
                        </td>

                        <td class="px-4 py-3 text-sm">
                            {{ $log->reason ?? '—' }}
                        </td>

                        <td class="px-4 py-3 text-sm">
                            {{ optional($log->performer)->fullname ?? optional($log->performer)->name ?? '—' }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-8">
                            No edits or deletions yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

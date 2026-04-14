<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Supervisor Dashboard
        </h2>
    </x-slot>

    <div class="py-6 px-6">

    <!-- 🔢 STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <!-- Pending -->
        <div class="bg-yellow-500 p-6 rounded-xl shadow-lg">
            <h5 class="text-lg font-semibold text-white">Pending</h5>
            <p class="text-3xl font-bold text-white mt-2">{{ $pending }}</p>
        </div>

        <!-- Approved -->
        <div class="bg-green-600 p-6 rounded-xl shadow-lg">
            <h5 class="text-lg font-semibold text-white">Approved</h5>
            <p class="text-3xl font-bold text-white mt-2">{{ $approved }}</p>
        </div>

        <!-- Rejected -->
        <div class="bg-red-600 p-6 rounded-xl shadow-lg">
            <h5 class="text-lg font-semibold text-white">Rejected</h5>
            <p class="text-3xl font-bold text-white mt-2">{{ $rejected }}</p>
        </div>

    </div>

    <!-- 📋 RECENT LEAVE REQUESTS -->
    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
            <h3 class="text-lg font-bold mb-4">Recent Leave Requests</h3>

            <table class="w-full border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 border">Name</th>
                        <th class="p-2 border">Type</th>
                        <th class="p-2 border">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($recent as $leave)
                    <tr>
                        <td class="p-2 border">
                            {{ $leave->user->name ?? 'N/A' }}
                        </td>
                        <td class="p-2 border">{{ $leave->type }}</td>
                        <td class="p-2 border">
                            <span class="px-2 py-1 rounded
                                {{ $leave->status == 'Pending' ? 'bg-yellow-200' : ($leave->status == 'Approved' ? 'bg-green-200' : 'bg-red-200') }}">
                                {{ $leave->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Pending User Approvals
        </h2>
    </x-slot>

    <div class="p-6">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">Name</th>
                    <th class="p-2">Username</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $user)
                <tr class="border-t">
                    <td class="p-2">{{ $user->fullname ?? $user->name }}</td>
                    <td class="p-2">{{ $user->username }}</td>
                    <td class="p-2">
                        <form method="POST" action="{{ route('admin.users.approve', $user->id) }}">
                            @csrf
                            <button class="bg-green-500 text-white px-3 py-1 rounded">
                                Approve
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>
<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">

        <h2 class="text-xl font-bold mb-4">Create Personnel</h2>

        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4">
                {{ session('success') }}

                <br><br>

                <strong>Login Details:</strong><br>
                Username: {{ session('username') }}<br>
                Password: {{ session('password') }}
            </div>
        @endif

        <form method="POST" action="/personnel/store">
            @csrf

            <input name="fullname" placeholder="Full Name" class="w-full mb-3 p-2 border" required>

            <input name="employee_id" placeholder="Employee ID" class="w-full mb-3 p-2 border" required>

            <input name="position" placeholder="Position" class="w-full mb-3 p-2 border">

            <input name="department" placeholder="Department" class="w-full mb-3 p-2 border">

            <button class="bg-green-600 text-white px-4 py-2 rounded">
                Create Personnel
            </button>

        </form>

    </div>
</x-app-layout>
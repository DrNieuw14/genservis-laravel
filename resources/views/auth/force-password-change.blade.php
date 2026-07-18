<x-guest-layout>

    <!-- TITLE -->
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Set a New Password</h2>
    <p class="text-sm text-gray-500 mb-6">
        This account was created with a temporary password. Please set your
        own password before continuing.
    </p>

    <form method="POST" action="{{ route('password.force.update') }}">
        @csrf
        @method('PUT')

        <!-- NEW PASSWORD -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('New Password')" />

            <x-text-input
                id="password"
                class="block mt-1 w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
                type="password"
                name="password"
                placeholder="Enter a new password"
                required
                autofocus
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />

            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
                type="password"
                name="password_confirmation"
                placeholder="Re-enter the new password"
                required
            />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- BUTTON -->
        <button type="submit"
            class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition shadow">
            Set Password and Continue
        </button>

    </form>

</x-guest-layout>

<x-guest-layout>

    <!-- TITLE -->
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Forgot Password</h2>
    <p class="text-sm text-gray-500 mb-6">
        {{ __('No problem. Enter your email address and we will send you a password reset link.') }}
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
                type="email"
                name="email"
                :value="old('email')"
                placeholder="Enter your email address"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit"
            class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition shadow">
            {{ __('Email Password Reset Link') }}
        </button>

        <p class="text-sm text-center mt-6">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                Back to login
            </a>
        </p>
    </form>
</x-guest-layout>

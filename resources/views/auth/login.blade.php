<x-guest-layout>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
    <div class="mb-6 text-center">
        <div class="inline-block px-4 py-2 bg-green-500 text-white rounded-lg shadow">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- TITLE -->
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back</h2>
    <p class="text-sm text-gray-500 mb-6">Login to your account</p>

    <!-- STATUS -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- USERNAME -->
        <div class="mb-4">
            <x-input-label for="username" :value="__('Username or Email')" />

            <x-text-input 
                id="username" 
                class="block mt-1 w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
                type="text"
                name="username"
                placeholder="Enter username or email"
                required 
                autofocus 
            />

            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- PASSWORD -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input 
                id="password"
                class="block mt-1 w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
                type="password"
                name="password"
                placeholder="Enter password"
                required 
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- REMEMBER -->
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center text-sm">
                <input type="checkbox" name="remember" class="mr-2">
                Remember me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:underline">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- BUTTON -->
        <button type="submit"
            class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition shadow">
            Log In
        </button>

        <!-- REGISTER -->
        <p class="text-sm text-center mt-6">
            Don’t have an account?
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                Register here
            </a>
        </p>

    </form>

</x-guest-layout>
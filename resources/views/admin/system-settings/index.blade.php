@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->
    <div class="mb-8">

        <h1 class="text-3xl font-bold text-white flex items-center gap-3">
            ⚙️ System Settings
        </h1>

        <p class="text-gray-200 mt-2">
            Maintenance mode and outgoing email configuration.
        </p>

    </div>

    @if(session('success'))

        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-8">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-8">
            {{ session('error') }}
        </div>

    @endif

    <!-- ========================================================= -->
    <!-- MAINTENANCE MODE -->
    <!-- ========================================================= -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">

        <h2 class="text-xl font-semibold text-gray-800 mb-2">
            🛠️ Maintenance Mode
        </h2>

        <p class="text-gray-500 mb-6">
            When enabled, everyone except users with System Settings access sees a
            "Under Maintenance" page. You will never lose access to this page yourself.
        </p>

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-gray-500">Current Status</p>

                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $settings['maintenance_mode'] ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">

                    {{ $settings['maintenance_mode'] ? 'Under Maintenance' : 'Live' }}

                </span>

            </div>

            <form method="POST" action="{{ route('admin.system-settings.maintenance-mode') }}">

                @csrf

                <input type="hidden" name="maintenance_mode" value="{{ $settings['maintenance_mode'] ? '0' : '1' }}">

                <button
                    type="submit"
                    onclick="return confirm('{{ $settings['maintenance_mode']
                        ? 'Bring the site back online for everyone?'
                        : 'Take the site offline for everyone except System Settings admins?' }}')"
                    class="{{ $settings['maintenance_mode']
                        ? 'bg-green-600 hover:bg-green-700'
                        : 'bg-red-600 hover:bg-red-700' }}
                        text-white px-5 py-2 rounded-lg font-semibold shadow transition">

                    {{ $settings['maintenance_mode'] ? '✅ Disable Maintenance Mode' : '⛔ Enable Maintenance Mode' }}

                </button>

            </form>

        </div>

    </div>

    <!-- ========================================================= -->
    <!-- EMAIL SETTINGS -->
    <!-- ========================================================= -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">

        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            📧 Email Settings
        </h2>

        <form method="POST" action="{{ route('admin.system-settings.email') }}">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Mailer</label>
                    <select name="mail_mailer"
                        class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <option value="smtp" {{ $settings['mail_mailer'] == 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="log" {{ $settings['mail_mailer'] == 'log' ? 'selected' : '' }}>Log (testing only)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Encryption</label>
                    <select name="mail_encryption"
                        class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <option value="" {{ !$settings['mail_encryption'] ? 'selected' : '' }}>None</option>
                        <option value="tls" {{ $settings['mail_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ $settings['mail_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">SMTP Host</label>
                    <input type="text" name="mail_host" value="{{ old('mail_host', $settings['mail_host']) }}" required
                        class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">SMTP Port</label>
                    <input type="number" name="mail_port" value="{{ old('mail_port', $settings['mail_port']) }}" required
                        class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">SMTP Username</label>
                    <input type="text" name="mail_username" value="{{ old('mail_username', $settings['mail_username']) }}"
                        class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        SMTP Password
                        @if($settings['mail_password_set'])
                            <span class="text-xs text-gray-400">(leave blank to keep current password)</span>
                        @endif
                    </label>
                    <input type="password" name="mail_password" placeholder="{{ $settings['mail_password_set'] ? '••••••••' : '' }}"
                        class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">From Address</label>
                    <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address']) }}" required
                        class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">From Name</label>
                    <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name']) }}" required
                        class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>

            </div>

            @error('mail_port')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror

            <div class="flex justify-end mt-6">

                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold shadow transition">

                    💾 Save Email Settings

                </button>

            </div>

        </form>

        <hr class="my-6">

        <div class="flex items-center justify-between">

            <p class="text-gray-500 text-sm">
                Send a test email to your own address ({{ auth()->user()->email }}) to confirm these settings work.
            </p>

            <form method="POST" action="{{ route('admin.system-settings.email.test') }}">

                @csrf

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold shadow transition whitespace-nowrap">

                    ✉️ Send Test Email

                </button>

            </form>

        </div>

    </div>

</div>

@endsection

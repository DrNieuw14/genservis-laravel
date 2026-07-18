@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            🔑 Reset Password
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Generate a new temporary password for an employee who forgot theirs. They'll be
            required to set their own password the next time they log in.
        </p>

    </div>

    <div class="border rounded-lg p-5 bg-gray-50 max-w-2xl">

        <label class="block mb-2 font-semibold">
            Employee
        </label>

        <select
            id="userSelect"
            class="user-select w-full"
            required>

            <option value="">
                -- Select Employee --
            </option>

            @foreach($users as $user)
                <option value="{{ $user->id }}">
                    {{ optional($user->personnel)->fullname ?? $user->name }}
                    ({{ $user->username }})
                </option>
            @endforeach

        </select>

        <button
            type="button"
            id="resetPasswordBtn"
            onclick="confirmResetPassword()"
            disabled
            class="mt-5 bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold px-6 py-3 rounded-lg shadow">

            🔑 Reset Password

        </button>

    </div>

</div>

<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

<style>

    .ts-control {
        border-radius: 0.5rem !important;
        border: 1px solid #d1d5db !important;
        padding: 0.75rem !important;
        min-height: 50px !important;
        box-shadow: none !important;
        font-size: 16px !important;
        --ts-pr-min: 2.75rem;
    }

    .ts-control input {
        font-size: 16px !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .ts-wrapper.single .ts-control {
        background: white !important;
        position: relative;
    }

    .ts-wrapper.single .ts-control::after {
        content: "";
        position: absolute;
        right: 1.1rem;
        top: 50%;
        width: 10px;
        height: 10px;
        margin-top: -6px;
        border-right: 2px solid #6b7280;
        border-bottom: 2px solid #6b7280;
        transform: rotate(45deg);
        pointer-events: none;
    }

    .ts-control:focus-within {
        border-color: #60a5fa !important;
        box-shadow: 0 0 0 2px rgba(96,165,250,0.3) !important;
    }

    .ts-dropdown {
        border-radius: 0.5rem !important;
        border: 1px solid #d1d5db !important;
        overflow: hidden;
        font-size: 15px !important;
    }

    .ts-dropdown .option {
        padding: 0.6rem 1rem !important;
    }

</style>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>

    const userTomSelect = new TomSelect('#userSelect', {
        create: false,
        dropdownParent: 'body',
        sortField: {
            field: 'text',
            direction: 'asc'
        }
    });

    const resetBtn = document.getElementById('resetPasswordBtn');

    document.getElementById('userSelect').addEventListener('change', function () {
        resetBtn.disabled = !this.value;
    });

    function confirmResetPassword()
    {
        const userId = document.getElementById('userSelect').value;

        if (!userId) {
            return;
        }

        const employeeName = userTomSelect.options[userId].text;

        document.getElementById('confirmResetName').textContent = employeeName;

        document.getElementById('confirmResetModal')
            .classList.remove('hidden');

        document.getElementById('confirmResetModal')
            .classList.add('flex');
    }

    function closeConfirmResetModal()
    {
        document.getElementById('confirmResetModal')
            .classList.add('hidden');

        document.getElementById('confirmResetModal')
            .classList.remove('flex');
    }

    function submitResetPassword()
    {
        const userId = document.getElementById('userSelect').value;

        const confirmBtn = document.getElementById('confirmResetBtn');

        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Resetting...';

        fetch(`{{ url('/admin/reset-password') }}/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content,
                'Accept': 'application/json'
            }
        })
        .then(async response => {

            const data = await response.json();

            if (!response.ok) {
                throw data;
            }

            return data;
        })
        .then(data => {

            closeConfirmResetModal();

            showPasswordResetModal(data);

            userTomSelect.clear();

            resetBtn.disabled = true;

        })
        .catch(error => {

            closeConfirmResetModal();

            alert(error.message || 'Unable to reset this password. Please try again.');

        })
        .finally(() => {
            confirmBtn.disabled = false;
            confirmBtn.textContent = '🔑 Reset Password';
        });
    }

    function showPasswordResetModal(data)
    {
        document.getElementById('resetEmployeeName').textContent =
            `${data.fullname} (${data.username})`;

        document.getElementById('resetUsername').textContent =
            data.username;

        document.getElementById('resetPassword_').textContent =
            data.temporary_password;

        document.getElementById('copyResetCredentialsBtn').textContent =
            '📋 Copy Credentials';

        document.getElementById('passwordResetModal')
            .classList.remove('hidden');

        document.getElementById('passwordResetModal')
            .classList.add('flex');
    }

    function closePasswordResetModal()
    {
        document.getElementById('passwordResetModal')
            .classList.add('hidden');

        document.getElementById('passwordResetModal')
            .classList.remove('flex');
    }

    function copyResetCredentials()
    {
        const username =
            document.getElementById('resetUsername').textContent;

        const password =
            document.getElementById('resetPassword_').textContent;

        const text = `Username: ${username}\nTemporary Password: ${password}`;

        navigator.clipboard.writeText(text).then(() => {

            const btn = document.getElementById('copyResetCredentialsBtn');

            btn.textContent = '✔ Copied!';

            setTimeout(() => {
                btn.textContent = '📋 Copy Credentials';
            }, 1500);

        });
    }

</script>

<!-- Confirm Reset Modal -->
<div
    id="confirmResetModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

        <h2 class="text-xl font-bold text-yellow-600 mb-3">
            ⚠ Confirm Password Reset
        </h2>

        <p class="text-gray-700">
            Reset the password for <strong id="confirmResetName"></strong>?
            Their current password will stop working immediately, and they'll need
            to set a new one the next time they log in.
        </p>

        <div class="flex justify-end gap-3 mt-6">

            <button
                type="button"
                onclick="closeConfirmResetModal()"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">
                Cancel
            </button>

            <button
                id="confirmResetBtn"
                type="button"
                onclick="submitResetPassword()"
                class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded">
                🔑 Reset Password
            </button>

        </div>

    </div>

</div>

<!-- Password Reset Success Modal -->
<div
    id="passwordResetModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 text-center">

        <div class="flex justify-center mb-4">

            <div class="bg-green-100 rounded-full p-4">
                <svg
                    class="h-8 w-8 text-green-600"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2.5">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 13l4 4L19 7" />

                </svg>
            </div>

        </div>

        <h2 class="text-xl font-bold text-gray-800">
            Password Reset Successfully
        </h2>

        <p id="resetEmployeeName" class="text-gray-500 mt-1 mb-5"></p>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-left space-y-3">

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Username
                </p>
                <p id="resetUsername" class="font-mono text-lg text-gray-800"></p>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Temporary Password
                </p>
                <p id="resetPassword_" class="font-mono text-lg text-gray-800"></p>
            </div>

        </div>

        <button
            id="copyResetCredentialsBtn"
            type="button"
            onclick="copyResetCredentials()"
            class="text-sm text-blue-600 hover:text-blue-800 font-semibold mt-3 mb-5">
            📋 Copy Credentials
        </button>

        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-lg p-3 mb-6 text-left">
            ⚠ Share these credentials with the employee directly so they can log in and set their own password.
        </div>

        <button
            type="button"
            onclick="closePasswordResetModal()"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            Got It
        </button>

    </div>

</div>

@endsection

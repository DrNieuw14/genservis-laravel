@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-white flex items-center gap-3">
            👤 Employee Onboarding
        </h1>

        <p class="text-gray-200 mt-2">
            Complete the employee information before granting ERP access.
        </p>

    </div>

    <div class="bg-white rounded-2xl shadow-2xl p-8">

    <h2 class="text-2xl font-bold text-gray-800">
        Employee Information
    </h2>

    <p class="text-gray-500 mt-2 mb-6">
        Review the registered account and complete the employee profile.
    </p>

        <div class="mb-4">

            <h3 class="text-xl font-bold text-gray-800 mb-2">
                Account Information
            </h3>

            <p class="text-gray-500 mb-6">
                Review the registration details before completing employee onboarding.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Registered Name
                    </label>

                    <div class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                        {{ $user->name }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>

                    <div class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                        {{ $user->username }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>

                    <div class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                        {{ $user->email }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Registration Status
                    </label>

                    <div class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">

                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-medium">
                            Pending Approval
                        </span>

                    </div>
                </div>

                       </div> <!-- End row -->

        </div> <!-- End Account Information -->

        <hr class="my-4">

            <form method="POST"
            action="{{ route('admin.users.complete-onboarding', $user) }}">
            @csrf

            <h3 class="text-xl font-bold text-gray-800 mb-2">
                Employment Information
            </h3>

            <p class="text-gray-500 mb-6">
                Complete the required employment details before activating the employee account.
            </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Employment Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Employment Type
                        </label>

                        <select
                            id="employment_type_id"
                            name="employment_type_id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                            required>

                            <option selected disabled>Select Employment Type</option>

                            @foreach($employmentTypes as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Employee ID --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Employee ID
                        </label>

                        <input
                        id="employee_id"
                        type="text"
                        readonly
                        placeholder="Auto-generated"
                        class="w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3">
                    </div>

    {{-- Department --}}

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>

                        <select
                            id="department_id"
                            name="department_id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                            required>

                            <option selected disabled>Select Department</option>

                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">
                                    {{ $department->department_name }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>

                        <select
                            id="position_id"
                            name="position_id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                            required>

                            <option value="">
                                Select Employment Type First
                            </option>

                        </select>

                    </div>

                </div>

                <hr class="my-4">

                <div class="flex items-center gap-4 mt-6">

                    <button
                        type="submit"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-xl shadow hover:bg-green-700 transition">

                        ✔ Complete Onboarding

                    </button>

                    <a
                        href="{{ route('admin.users.pending') }}"
                        class="inline-flex items-center px-6 py-3 bg-gray-300 text-gray-800 rounded-xl hover:bg-gray-400 transition">

                        Cancel

                    </a>

                </div>
             </form>

        </div>

    </div>

  @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const employmentType = document.getElementById('employment_type_id');
    const employeeId = document.getElementById('employee_id');
    const position = document.getElementById('position_id');

    employmentType.addEventListener('change', function () {

        const employmentTypeId = this.value;

        // Generate Employee ID
        fetch(`/admin/users/generate-employee-id/${employmentTypeId}`)
            .then(response => response.json())
            .then(data => {
                employeeId.value = data.employee_id;
            })
            .catch(error => {
                console.error(error);
                employeeId.value = '';
            });

        // Load Positions
        fetch(`/admin/users/employment-type/${employmentTypeId}/positions`)
            .then(response => response.json())
            .then(data => {

                position.innerHTML =
                    '<option value="">Select Position</option>';

                data.forEach(item => {

                    position.innerHTML += `
                        <option value="${item.id}">
                            ${item.position_name}
                        </option>
                    `;

                });

            })
            .catch(error => {
                console.error(error);

                position.innerHTML =
                    '<option value="">Unable to load positions</option>';
            });

    });

});
</script>
@endpush

@endsection
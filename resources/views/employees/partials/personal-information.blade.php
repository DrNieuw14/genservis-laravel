<div class="bg-white rounded-xl shadow mt-6">

    <div class="flex justify-between items-center border-b px-6 py-4">

        <h2 class="text-xl font-semibold text-gray-800">
            👤 Personal Information
        </h2>

        @if($employee->profile)

            <a href="{{ route('employees.profile.edit', $employee) }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

                ✏ Edit Personal Information

            </a>

        @else

            <a href="{{ route('employees.profile.create', $employee) }}"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">

                + Add Personal Information

            </a>

        @endif

    </div>

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-gray-500">Birthdate</p>
                <p class="font-medium">
                    {{ data_get($employee, 'profile.birthdate', 'Not yet provided') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Gender</p>
                <p class="font-medium">
                    {{ data_get($employee, 'profile.gender', 'Not yet provided') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Civil Status</p>
                <p class="font-medium">
                    {{ data_get($employee, 'profile.civil_status', 'Not yet provided') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Nationality</p>
                <p class="font-medium">
                    {{ data_get($employee, 'profile.nationality', 'Not yet provided') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Religion</p>
                <p class="font-medium">
                    {{ data_get($employee, 'profile.religion', 'Not yet provided') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Blood Type</p>
                <p class="font-medium">
                    {{ data_get($employee, 'profile.blood_type', 'Not yet provided') }}
                </p>
            </div>

        </div>

    </div>

</div>
@csrf

<div class="bg-white rounded-xl shadow">

    <div class="border-b px-6 py-4">

        <h2 class="text-xl font-semibold text-gray-800">

            👤 Personal Information

        </h2>

        <p class="text-gray-500 text-sm mt-1">

            Maintain the employee's personal profile.

        </p>

    </div>

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Birthdate -->

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">

                    Birthdate

                </label>

                <input
                    type="date"
                    name="birthdate"
                    value="{{ old('birthdate', optional($employee->profile)->birthdate) }}"
                    class="w-full rounded-lg border-gray-300">

            </div>

            <!-- Gender -->

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">

                    Gender

                </label>

                <select
                    name="gender"
                    class="w-full rounded-lg border-gray-300">

                    <option value="">Select Gender</option>

                    <option value="Male"
                        @selected(old('gender', optional($employee->profile)->gender) == 'Male')>

                        Male

                    </option>

                    <option value="Female"
                        @selected(old('gender', optional($employee->profile)->gender) == 'Female')>

                        Female

                    </option>

                </select>

            </div>

            <!-- Civil Status -->

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">

                    Civil Status

                </label>

                <select
                    name="civil_status"
                    class="w-full rounded-lg border-gray-300">

                    <option value="">Select</option>

                    @foreach([
                        'Single',
                        'Married',
                        'Widowed',
                        'Separated'
                    ] as $status)

                        <option
                            value="{{ $status }}"
                            @selected(old('civil_status', optional($employee->profile)->civil_status) == $status)>

                            {{ $status }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- Nationality -->

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">

                    Nationality

                </label>

                <input
                    type="text"
                    name="nationality"
                    value="{{ old('nationality', optional($employee->profile)->nationality) }}"
                    class="w-full rounded-lg border-gray-300">

            </div>

            <!-- Religion -->

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">

                    Religion

                </label>

                <input
                    type="text"
                    name="religion"
                    value="{{ old('religion', optional($employee->profile)->religion) }}"
                    class="w-full rounded-lg border-gray-300">

            </div>

            <!-- Blood Type -->

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">

                    Blood Type

                </label>

                <select
                    name="blood_type"
                    class="w-full rounded-lg border-gray-300">

                    <option value="">Select</option>

                    @foreach([
                        'A+','A-',
                        'B+','B-',
                        'AB+','AB-',
                        'O+','O-'
                    ] as $type)

                        <option
                            value="{{ $type }}"
                            @selected(old('blood_type', optional($employee->profile)->blood_type) == $type)>

                            {{ $type }}

                        </option>

                    @endforeach

                </select>

            </div>

        </div>

    </div>

    <div class="border-t px-6 py-4 flex justify-end gap-3">

        <a
            href="{{ route('employees.show', $employee) }}"
            class="px-5 py-2 rounded-lg border">

            Cancel

        </a>

        <button
            type="submit"
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">

            Save Personal Information

        </button>

    </div>

</div>
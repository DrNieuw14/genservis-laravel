<div class="bg-white rounded-2xl shadow-sm border border-gray-200 mt-6">

    <div class="flex items-center justify-between px-6 py-4 border-b">

        <div>
            <h2 class="text-lg font-semibold text-gray-800">
                🎓 Educational Background
            </h2>

            <p class="text-sm text-gray-500">
                Employee educational qualifications.
            </p>
        </div>

        <button
            type="button"
            id="openEducationModal"
            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">

            + Add Education

        </button>

    </div>

    <div class="p-6">

        @if($employee->educations->count())

            <table class="min-w-full">

                <thead>

                    <tr class="border-b">

                        <th class="text-left py-2">Level</th>
                        <th class="text-left py-2">School</th>
                        <th class="text-left py-2">Course</th>
                        <th class="text-left py-2">Year</th>
                        <th class="text-center py-2">Actions</th>

                    </tr>

                </thead>

                <tbody>

                @foreach($employee->educations as $education)

                    <tr class="border-b">

                        <td>{{ $education->education_level }}</td>

                        <td>{{ $education->school_name }}</td>

                        <td>{{ $education->degree_course ?? '-' }}</td>

                        <td>{{ $education->year_graduated ?? '-' }}</td>

                        <td class="text-center">

                        <div class="flex justify-center gap-2">

                            <button
                                type="button"
                                class="editEducationBtn
                                    text-blue-600
                                    hover:text-blue-800"

                                data-id="{{ $education->id }}"
                                data-level="{{ $education->education_level }}"
                                data-school="{{ $education->school_name }}"
                                data-course="{{ $education->degree_course }}"
                                data-highest="{{ $education->highest_level }}"
                                data-from="{{ $education->from_year }}"
                                data-to="{{ $education->to_year }}"
                                data-graduated="{{ $education->year_graduated }}"
                                data-honors="{{ $education->honors }}"
                                data-units="{{ $education->units_earned }}">

                                ✏ Edit

                            </button>

                            <button
                                type="button"
                                class="deleteEducationBtn text-red-600 hover:text-red-800"

                                data-id="{{ $education->id }}">

                                🗑 Delete

                            </button>

                        </div>

                    </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        @else

            <div class="text-center py-8 text-gray-500">

                No educational background has been added.

            </div>

        @endif

    </div>

</div>

@include('employees.partials.modals.education-create')

@include('employees.partials.modals.education-edit')

@include('employees.partials.modals.education-delete')
<div class="bg-white rounded-xl shadow">

    <div class="p-8 border-b">

        <div class="flex justify-between items-start">

            <div>

                <h1 class="text-3xl font-bold text-gray-800">
                    👤 {{ $employee->fullname }}
                </h1>

                <p class="text-gray-500 mt-2">

                    Employee ID:

                    <span class="font-semibold">

                        {{ $employee->employee_id }}

                    </span>

                </p>

            </div>

            <span class="px-4 py-2 rounded-full bg-green-100 text-green-700">

                {{ $employee->status }}

            </span>

        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-8">

        <div>

            <p class="text-gray-500 text-sm">
                Department
            </p>

            <h3 class="font-semibold">

                {{ $employee->departmentRecord?->department_name ?? '-' }}

            </h3>

        </div>

        <div>

            <p class="text-gray-500 text-sm">
                Position
            </p>

            <h3 class="font-semibold">

                {{ $employee->positionRecord?->position_name ?? '-' }}

            </h3>

        </div>

        <div>

            <p class="text-gray-500 text-sm">
                Employment Type
            </p>

            <h3 class="font-semibold">

                {{ $employee->employmentType?->name ?? '-' }}

            </h3>

        </div>

        <div>

            <p class="text-gray-500 text-sm">
                System Role(s)
            </p>

            <h3 class="font-semibold">

                {{ $employee->user?->allRoles()->pluck('name')->join(', ') ?: 'Unassigned' }}

            </h3>

        </div>

    </div>

</div>
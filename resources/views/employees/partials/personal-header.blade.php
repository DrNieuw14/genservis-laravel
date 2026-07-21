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

            <div class="flex flex-col items-end gap-3">

                @if(auth()->user()->hasPermission('edit-employees'))
                    <a href="{{ route('employees.edit', $employee->id) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        ✏ Edit Employment Info
                    </a>
                @endif

                <span class="px-4 py-2 rounded-full {{ $employee->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">

                    {{ $employee->status }}

                </span>

                <div class="text-center">

                    <img src="{{ $qrDataUri }}" alt="QR Code" width="90" height="90">

                    <a href="{{ route('employees.id-card', $employee->id) }}"
                       target="_blank"
                       class="block mt-1 text-sm text-blue-600 hover:underline">
                        🖨 Print ID Card
                    </a>

                </div>

            </div>

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
                Employment Status
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
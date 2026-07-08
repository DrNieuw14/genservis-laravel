<div class="bg-white rounded-xl shadow-md p-6 mt-6">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-xl font-bold text-gray-800">
                📞 Contact Information
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Employee contact details and emergency contact information.
            </p>
        </div>

        @if($employee->contact)

            <a href="{{ route('employees.contact.edit', $employee) }}"
               class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg shadow">

                ✏ Edit Contact

            </a>

        @else

            <a href="{{ route('employees.contact.create', $employee) }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow">

                ➕ Add Contact

            </a>

        @endif

    </div>

    @if($employee->contact)

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-500">Primary Email</label>
                <p class="font-medium">{{ $employee->contact->primary_email }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Alternate Email</label>
                <p class="font-medium">
                    {{ $employee->contact->alternate_email ?? '-' }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Mobile Number</label>
                <p class="font-medium">{{ $employee->contact->mobile_number }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Telephone Number</label>
                <p class="font-medium">
                    {{ $employee->contact->telephone_number ?? '-' }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Emergency Contact</label>
                <p class="font-medium">
                    {{ $employee->contact->emergency_contact_person }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Relationship</label>
                <p class="font-medium">
                    {{ $employee->contact->emergency_relationship }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Emergency Number</label>
                <p class="font-medium">
                    {{ $employee->contact->emergency_contact_number }}
                </p>
            </div>

        </div>

    @else

        <div class="rounded-lg border border-dashed border-gray-300 p-8 text-center">

            <p class="text-gray-500">
                No contact information has been added yet.
            </p>

        </div>

    @endif

</div>
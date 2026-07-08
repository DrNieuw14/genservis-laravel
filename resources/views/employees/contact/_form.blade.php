<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Primary Email -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Primary Email <span class="text-red-500">*</span>
        </label>

        <input
            type="email"
            name="primary_email"
            value="{{ old('primary_email', $contact->primary_email ?? '') }}"
            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            required>

        @error('primary_email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Alternate Email -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Alternate Email
        </label>

        <input
            type="email"
            name="alternate_email"
            value="{{ old('alternate_email', $contact->alternate_email ?? '') }}"
            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">

        @error('alternate_email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Mobile Number -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Mobile Number <span class="text-red-500">*</span>
        </label>

        <input
            
            type="text"
            name="mobile_number"
            value="{{ old('mobile_number', $contact->mobile_number ?? '') }}"
            maxlength="11"
            pattern="09[0-9]{9}"
            inputmode="numeric"
            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            placeholder="09123456789"
            required>

        @error('mobile_number')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Telephone Number -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Telephone Number
        </label>

        <input
            type="text"
            name="telephone_number"
            value="{{ old('telephone_number', $contact->telephone_number ?? '') }}"
            maxlength="20"
            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
        @error('telephone_number')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Emergency Contact Person -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Emergency Contact Person <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            name="emergency_contact_person"
            value="{{ old('emergency_contact_person', $contact->emergency_contact_person ?? '') }}"
            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            required>

        @error('emergency_contact_person')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Relationship -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Relationship <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            name="emergency_relationship"
            value="{{ old('emergency_relationship', $contact->emergency_relationship ?? '') }}"
            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            placeholder="Father, Mother, Spouse, Brother, Sister"
            required>

        @error('emergency_relationship')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Emergency Contact Number -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Emergency Contact Number <span class="text-red-500">*</span>
        </label>

        <input
            
            type="text"
            name="emergency_contact_number"
            value="{{ old('emergency_contact_number', $contact->emergency_contact_number ?? '') }}"
            maxlength="11"
            pattern="09[0-9]{9}"
            inputmode="numeric"
            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            placeholder="09123456789"
            required>

        @error('emergency_contact_number')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>

<div class="flex justify-end gap-3 mt-8">

    <a href="{{ route('employees.show', $employee) }}"
       class="px-5 py-2.5 bg-gray-300 hover:bg-gray-400 rounded-xl text-gray-800">

        Cancel

    </a>

    <button
        type="submit"
        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow">

        💾 Save Contact Information

    </button>

</div>
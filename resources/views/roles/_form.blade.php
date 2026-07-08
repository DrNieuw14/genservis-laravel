<div class="p-6 space-y-6">

    <div>

        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Role Name
        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $role->name ?? '') }}"
            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-green-500 focus:ring focus:ring-green-200"
            required>

        @error('name')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror

    </div>

    <div>

        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Description
        </label>

        <textarea
            name="description"
            rows="4"
            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-green-500 focus:ring focus:ring-green-200">{{ old('description', $role->description ?? '') }}</textarea>

        @error('description')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror

    </div>

    <div>

        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Status
        </label>

        <select
            name="status"
            class="w-full rounded-lg border border-gray-300 px-4 py-2">

            <option value="1" {{ old('status', $role->status ?? 1) ? 'selected' : '' }}>
                Active
            </option>

            <option value="0" {{ old('status', $role->status ?? 1) == 0 ? 'selected' : '' }}>
                Inactive
            </option>

        </select>

    </div>

    <div class="flex justify-end gap-3 pt-6 border-t">

        <a href="{{ route('roles.index') }}"
           class="px-5 py-2 rounded-lg border border-gray-300">

            Cancel

        </a>

        <button
            type="submit"
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">

            Save Role

        </button>

    </div>

</div>
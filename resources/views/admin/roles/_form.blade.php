<div class="space-y-6">

    <div>
        <label class="block text-sm font-medium text-gray-700">
            Role Name
        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $role->name ?? '') }}"
            class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
            required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">
            Description
        </label>

        <textarea
            name="description"
            rows="3"
            class="w-full mt-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('description', $role->description ?? '') }}</textarea>
    </div>

    <div>
        {{-- Hidden field must precede the checkbox: browsers submit fields in
             DOM order, and PHP takes the last value for a repeated key, so an
             unchecked box still submits status=0 instead of omitting it. --}}
        <input type="hidden" name="status" value="0">

        <label class="inline-flex items-center gap-2">

            <input
                type="checkbox"
                name="status"
                value="1"
                {{ old('status', $role->status ?? true) ? 'checked' : '' }}
                class="rounded border-gray-300 text-green-600 focus:ring-green-500">

            <span class="text-sm font-medium text-gray-700">
                Active
            </span>

        </label>
    </div>

</div>
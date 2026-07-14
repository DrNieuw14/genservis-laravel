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

</div>
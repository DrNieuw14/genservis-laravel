<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Education Level -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Education Level <span class="text-red-500">*</span>
        </label>

        <select
            id="{{ $prefix ?? '' }}education_level"
            name="education_level"
            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
            required>

            <option value="">-- Select Education Level --</option>

            <option value="Elementary">Elementary</option>

            <option value="Secondary">Secondary</option>

            <option value="Vocational">Vocational</option>

            <option value="College">College</option>

            <option value="Graduate Studies">Graduate Studies</option>

        </select>
    </div>

    <!-- School Name -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            School Name <span class="text-red-500">*</span>
        </label>

        <input
            id="{{ $prefix ?? '' }}school_name"
            type="text"
            name="school_name"
            value="{{ old('school_name') }}"
            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
            placeholder="Enter school name"
            required>
    </div>

    <!-- Degree -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Degree / Course
        </label>

        <input
            id="{{ $prefix ?? '' }}degree_course"
            type="text"
            name="degree_course"
            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
            placeholder="e.g. Bachelor of Science in Information Technology">
    </div>

    <!-- Highest Level -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Highest Level / Units Earned
        </label>

        <input
            id="{{ $prefix ?? '' }}highest_level"
            type="text"
            name="highest_level"
            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
            placeholder="e.g. Third Year">
    </div>

</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            From Year
        </label>

        <input
            id="{{ $prefix ?? '' }}from_year"
            type="number"
            name="from_year"
            min="1900"
            max="{{ date('Y') }}"
            class="w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            To Year
        </label>

        <input
            id="{{ $prefix ?? '' }}to_year"
            type="number"
            name="to_year"
            min="1900"
            max="{{ date('Y') }}"
            class="w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Year Graduated
        </label>

        <input
            id="{{ $prefix ?? '' }}year_graduated"
            type="number"
            name="year_graduated"
            min="1900"
            max="{{ date('Y') }}"
            class="w-full rounded-lg border-gray-300">
    </div>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Honors
        </label>

        <input
            id="{{ $prefix ?? '' }}honors"
            type="text"
            name="honors"
            class="w-full rounded-lg border-gray-300"
            placeholder="Cum Laude, With Honors, etc.">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Units Earned
        </label>

        <input
            id="{{ $prefix ?? '' }}units_earned"
            type="text"
            name="units_earned"
            class="w-full rounded-lg border-gray-300"
            placeholder="e.g. 36 Units">
    </div>

</div>
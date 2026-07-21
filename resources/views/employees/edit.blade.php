@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ✏ Edit Employment Info
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $employee->fullname }} — {{ $employee->employee_id }}
            </p>
        </div>

        <x-back-button :href="route('employees.show', $employee->id)" />
    </div>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>

            <div class="flex items-center gap-4">

                <img
                    id="photo-preview"
                    src="{{ $employee->photo_url ?? '' }}"
                    class="{{ $employee->photo_url ? '' : 'hidden' }} w-24 h-24 object-cover rounded-full border"
                    alt="Preview">

                <div class="flex-1">

                    <input type="file"
                           name="photo"
                           accept="image/*"
                           onchange="previewEmployeePhoto(this)"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">

                    @if($employee->photo_url)
                        <label class="inline-flex items-center gap-2 mt-2 text-sm text-red-600">
                            <input type="checkbox" name="remove_photo" value="1">
                            Remove current photo
                        </label>
                    @endif

                </div>

            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="fullname" value="{{ old('fullname', $employee->fullname) }}"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    <option value="Active" @selected(old('status', $employee->status) === 'Active')>Active</option>
                    <option value="Inactive" @selected(old('status', $employee->status) === 'Inactive')>Inactive</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select name="department_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">— None —</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" @selected(old('department_id', $employee->department_id) == $department->id)>
                            {{ $department->department_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Employment Status</label>
                <select id="employment_type_id" name="employment_type_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">— None —</option>

                    @php $plantillaTypes = $employmentTypes->filter(fn($t) => str_starts_with($t->name, 'Plantilla')); @endphp

                    @if($plantillaTypes->isNotEmpty())
                        <optgroup label="Plantilla">
                            @foreach($plantillaTypes as $type)
                                <option value="{{ $type->id }}" @selected(old('employment_type_id', $employee->employment_type_id) == $type->id)>
                                    {{ str_contains($type->name, 'Temporary') ? 'Temporary' : 'Regular' }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endif

                    @foreach($employmentTypes->reject(fn($t) => str_starts_with($t->name, 'Plantilla')) as $type)
                        <option value="{{ $type->id }}" @selected(old('employment_type_id', $employee->employment_type_id) == $type->id)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                <select id="position_id" name="position_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">— None —</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" @selected(old('position_id', $employee->position_id) == $position->id)>
                            {{ $position->position_name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl shadow">
                ✔ Save Changes
            </button>

            <a href="{{ route('employees.show', $employee->id) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-xl">
                Cancel
            </a>
        </div>

    </form>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const employmentType = document.getElementById('employment_type_id');
    const position = document.getElementById('position_id');
    const currentPositionId = "{{ old('position_id', $employee->position_id) }}";

    employmentType.addEventListener('change', function () {

        const employmentTypeId = this.value;

        if (!employmentTypeId) {
            position.innerHTML = '<option value="">— None —</option>';
            return;
        }

        fetch(`/admin/users/employment-type/${employmentTypeId}/positions`)
            .then(response => response.json())
            .then(data => {

                position.innerHTML = '<option value="">Select Position</option>';

                data.forEach(item => {
                    const selected = String(item.id) === String(currentPositionId) ? 'selected' : '';
                    position.innerHTML += `
                        <option value="${item.id}" ${selected}>
                            ${item.position_name}
                        </option>
                    `;
                });

            })
            .catch(error => {
                console.error(error);
                position.innerHTML = '<option value="">Unable to load positions</option>';
            });

    });

});

function previewEmployeePhoto(input)
{
    const preview = document.getElementById('photo-preview');

    if (!input.files || !input.files[0]) {
        preview.classList.add('hidden');
        preview.src = '';
        return;
    }

    preview.src = URL.createObjectURL(input.files[0]);
    preview.classList.remove('hidden');
}
</script>
@endpush

@endsection

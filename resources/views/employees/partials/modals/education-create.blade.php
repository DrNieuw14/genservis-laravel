<div id="educationModal"
     class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl mx-4">

        <div class="flex justify-between items-center border-b px-6 py-4">

            <h2 class="text-xl font-bold">
                Add Educational Background
            </h2>

            <button
                type="button"
                id="closeEducationModal"
                class="text-gray-500 hover:text-red-600 text-2xl">

                &times;

            </button>

        </div>

        <form
            action="{{ route('employees.education.store', $employee) }}"
            method="POST">

            @csrf

            <input
                type="hidden"
                name="personnel_id"
                value="{{ $employee->id }}">

            <div class="p-6">

                @include('employees.partials.education-form', [
                    'prefix' => 'create_'
                ])

            </div>

            <div class="flex justify-end gap-3 border-t px-6 py-4">

                <button
                    type="button"
                    id="closeEducationModalFooter"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">

                    Save Education

                </button>

            </div>

        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const educationModal = document.getElementById('educationModal');

    const openBtn = document.getElementById('openEducationModal');

    const closeBtn = document.getElementById('closeEducationModal');

    openBtn.addEventListener('click', function () {
        educationModal.classList.remove('hidden');
    });

    closeBtn.addEventListener('click', function () {
        educationModal.classList.add('hidden');
    });

});
</script>
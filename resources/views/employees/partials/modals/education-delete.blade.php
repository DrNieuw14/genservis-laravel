<div id="educationDeleteModal"
     class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">

        <div class="px-6 py-6">

            <h2 class="text-xl font-bold text-red-600">

                Delete Educational Background

            </h2>

            <p class="mt-4 text-gray-600">

                Are you sure you want to delete this educational background?

            </p>

            <form
                id="educationDeleteForm"
                method="POST"
                class="mt-6">

                @csrf

                @method('DELETE')

                <div class="flex justify-end gap-3">

                    <button
                        type="button"
                        id="cancelDeleteEducation"
                        class="px-4 py-2 border rounded-lg">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">

                        Delete

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const deleteModal =
        document.getElementById('educationDeleteModal');

    const deleteForm =
        document.getElementById('educationDeleteForm');

    /*
    |--------------------------------------------------------------------------
    | Open Delete Modal
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.deleteEducationBtn')
        .forEach(button => {

            button.addEventListener('click', function () {

                deleteForm.action =
                    '/employees/{{ $employee->id }}/education/' + this.dataset.id;

                deleteModal.classList.remove('hidden');

            });

        });

    /*
    |--------------------------------------------------------------------------
    | Close Delete Modal
    |--------------------------------------------------------------------------
    */

    document.getElementById('cancelDeleteEducation')
        .addEventListener('click', function () {

            deleteModal.classList.add('hidden');

        });

});
</script>
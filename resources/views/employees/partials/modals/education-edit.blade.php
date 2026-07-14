<div id="educationEditModal"
     class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl mx-4">

        <div class="flex justify-between items-center border-b px-6 py-4">

            <h2 class="text-xl font-bold">

                Edit Educational Background

            </h2>

            <button
                type="button"
                id="closeEducationEditModal"
                class="text-gray-500 hover:text-red-600 text-2xl">

                &times;

            </button>

        </div>

        <form
            id="educationEditForm"
            method="POST">

            @csrf

            @method('PUT')

            <div class="p-6">

                @include('employees.partials.education-form', [
                    'prefix' => 'edit_'
                ])

            </div>

            <div class="flex justify-end gap-3 border-t px-6 py-4">

                <button
                    type="button"
                    id="cancelEducationEdit"

                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">

                    Cancel

                </button>

                <button
                    type="submit"

                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">

                    Update Education

                </button>

            </div>

        </form>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const modal =
        document.getElementById('educationEditModal');

    const form =
        document.getElementById('educationEditForm');

    document.querySelectorAll('.editEducationBtn')
        .forEach(button => {

            button.addEventListener('click', function () {

                /*
                |--------------------------------------------------------------------------
                | Populate Fields
                |--------------------------------------------------------------------------
                */

                document.getElementById('edit_education_level').value =
                    this.dataset.level;

                document.getElementById('edit_school_name').value =
                    this.dataset.school;

                document.getElementById('edit_degree_course').value =
                    this.dataset.course;

                document.getElementById('edit_highest_level').value =
                    this.dataset.highest;

                document.getElementById('edit_from_year').value =
                    this.dataset.from;

                document.getElementById('edit_to_year').value =
                    this.dataset.to;

                document.getElementById('edit_year_graduated').value =
                    this.dataset.graduated;

                document.getElementById('edit_honors').value =
                    this.dataset.honors;

                document.getElementById('edit_units_earned').value =
                    this.dataset.units;

                /*
                |--------------------------------------------------------------------------
                | Update Form Action
                |--------------------------------------------------------------------------
                */

                form.action =
                    '/employees/{{ $employee->id }}/education/' + this.dataset.id;

                /*
                |--------------------------------------------------------------------------
                | Open Modal
                |--------------------------------------------------------------------------
                */

                modal.classList.remove('hidden');

            });

        });

});
</script>
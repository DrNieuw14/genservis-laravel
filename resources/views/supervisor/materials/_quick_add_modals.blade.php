<!-- Quick Add Category Modal -->
<div id="categoryQuickAddModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

        <h2 class="text-2xl font-bold text-gray-800 mb-1">➕ Add New Category</h2>
        <p class="text-gray-500 mb-4">Not in the list? Add it here without leaving this form.</p>

        <div id="categoryQuickAddError" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded"></div>

        <form id="categoryQuickAddForm">
            <label class="block mb-1 font-semibold text-sm">Category Name</label>
            <input type="text" name="name" class="w-full border rounded-lg p-3" required>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeCategoryQuickAddModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">Cancel</button>
                <button id="categoryQuickAddSubmitBtn" type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">✔ Create Category</button>
            </div>
        </form>

    </div>
</div>

<!-- Quick Add Unit Modal -->
<div id="unitQuickAddModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

        <h2 class="text-2xl font-bold text-gray-800 mb-1">➕ Add New Unit</h2>
        <p class="text-gray-500 mb-4">Not in the list? Add it here without leaving this form.</p>

        <div id="unitQuickAddError" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded"></div>

        <form id="unitQuickAddForm">
            <label class="block mb-1 font-semibold text-sm">Unit Name</label>
            <input type="text" name="name" placeholder="e.g. pcs, box, ream" class="w-full border rounded-lg p-3" required>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeUnitQuickAddModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">Cancel</button>
                <button id="unitQuickAddSubmitBtn" type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">✔ Create Unit</button>
            </div>
        </form>

    </div>
</div>

<!-- Quick Add Department Modal -->
<div id="departmentQuickAddModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

        <h2 class="text-2xl font-bold text-gray-800 mb-1">➕ Add New Department</h2>
        <p class="text-gray-500 mb-4">Not in the list? Add it here without leaving this form.</p>

        <div id="departmentQuickAddError" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded"></div>

        <form id="departmentQuickAddForm">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block mb-1 font-semibold text-sm">Department Name</label>
                    <input type="text" name="department_name" class="w-full border rounded-lg p-3" required>
                </div>
                <div>
                    <label class="block mb-1 font-semibold text-sm">Department Code <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="text" name="department_code" class="w-full border rounded-lg p-3">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeDepartmentQuickAddModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">Cancel</button>
                <button id="departmentQuickAddSubmitBtn" type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">✔ Create Department</button>
            </div>
        </form>

    </div>
</div>

<script>

    function openCategoryQuickAddModal() {
        document.getElementById('categoryQuickAddForm').reset();
        document.getElementById('categoryQuickAddError').classList.add('hidden');
        document.getElementById('categoryQuickAddModal').classList.remove('hidden');
        document.getElementById('categoryQuickAddModal').classList.add('flex');
    }

    function closeCategoryQuickAddModal() {
        document.getElementById('categoryQuickAddModal').classList.add('hidden');
        document.getElementById('categoryQuickAddModal').classList.remove('flex');
    }

    function openUnitQuickAddModal() {
        document.getElementById('unitQuickAddForm').reset();
        document.getElementById('unitQuickAddError').classList.add('hidden');
        document.getElementById('unitQuickAddModal').classList.remove('hidden');
        document.getElementById('unitQuickAddModal').classList.add('flex');
    }

    function closeUnitQuickAddModal() {
        document.getElementById('unitQuickAddModal').classList.add('hidden');
        document.getElementById('unitQuickAddModal').classList.remove('flex');
    }

    function openDepartmentQuickAddModal() {
        document.getElementById('departmentQuickAddForm').reset();
        document.getElementById('departmentQuickAddError').classList.add('hidden');
        document.getElementById('departmentQuickAddModal').classList.remove('hidden');
        document.getElementById('departmentQuickAddModal').classList.add('flex');
    }

    function closeDepartmentQuickAddModal() {
        document.getElementById('departmentQuickAddModal').classList.add('hidden');
        document.getElementById('departmentQuickAddModal').classList.remove('flex');
    }

    // Shared submit handler: posts the form via fetch, injects the new
    // option into the target <select> and selects it, then closes the
    // modal — or shows the error inline without closing.
    function bindQuickAddForm(formId, url, errorBoxId, submitBtnId, submitLabel, selectId, buildOption) {

        document.getElementById(formId).addEventListener('submit', function (e) {

            e.preventDefault();

            const submitBtn = document.getElementById(submitBtnId);
            const errorBox = document.getElementById(errorBoxId);

            errorBox.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating...';

            const formData = new FormData(this);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw data;
                return data;
            })
            .then(data => {

                const { value, text } = buildOption(data);

                const select = document.getElementById(selectId);
                const option = new Option(text, value);
                select.add(option);
                select.value = String(value);

                if (formId === 'categoryQuickAddForm') closeCategoryQuickAddModal();
                if (formId === 'unitQuickAddForm') closeUnitQuickAddModal();
                if (formId === 'departmentQuickAddForm') closeDepartmentQuickAddModal();

            })
            .catch(error => {

                let message = error.message || 'Unable to create. Please check the form and try again.';

                if (error.errors) {
                    message = Object.values(error.errors).map(messages => messages[0]).join(' ');
                }

                errorBox.textContent = message;
                errorBox.classList.remove('hidden');

            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = submitLabel;
            });

        });
    }

    bindQuickAddForm(
        'categoryQuickAddForm',
        '{{ route('materials.quick-add-category') }}',
        'categoryQuickAddError',
        'categoryQuickAddSubmitBtn',
        '✔ Create Category',
        'categorySelect',
        (data) => ({ value: data.id, text: data.name })
    );

    bindQuickAddForm(
        'unitQuickAddForm',
        '{{ route('materials.quick-add-unit') }}',
        'unitQuickAddError',
        'unitQuickAddSubmitBtn',
        '✔ Create Unit',
        'unitSelect',
        (data) => ({ value: data.id, text: data.name })
    );

    bindQuickAddForm(
        'departmentQuickAddForm',
        '{{ route('materials.quick-add-department') }}',
        'departmentQuickAddError',
        'departmentQuickAddSubmitBtn',
        '✔ Create Department',
        'departmentSelect',
        (data) => ({ value: data.id, text: data.department_name })
    );

</script>

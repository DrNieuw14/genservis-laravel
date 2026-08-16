@php
    $jobRequestTemplates = [
        'electrical' => [
            'label' => '⚡ Electrical',
            'nature_of_request' => 'Electrical repair — [specify item/location]',
            'work_summary' => "Inspection and repair/replacement of electrical wiring, outlets, switches, circuit breakers, or lighting fixtures in [specific area]. Issue observed: [e.g. flickering lights, no power, exposed wiring].",
            'work_category' => 'Electrical',
        ],
        'plumbing' => [
            'label' => '🔧 Plumbing',
            'nature_of_request' => 'Plumbing repair — [specify fixture/location]',
            'work_summary' => "Inspection and repair/replacement of pipes, faucets, valves, or fixtures (e.g., toilet, sink, drain) in [specific area]. Issue observed: [e.g. leak, clogging, no water flow].",
            'work_category' => 'Plumbing',
        ],
        'painting' => [
            'label' => '🎨 Painting',
            'nature_of_request' => 'Painting work — [specify area/surface]',
            'work_summary' => "Surface preparation and repainting of walls/ceiling in [specific area]. Color/coverage needed: [specify]. Note any peeling or damage on the current surface.",
            'work_category' => 'Painting',
        ],
        'tiling' => [
            'label' => '🧱 Retiling / Flooring',
            'nature_of_request' => 'Retiling/flooring repair — [specify area]',
            'work_summary' => "Removal of damaged/old tiles and installation of new floor/wall tiles in [specific area]. Extent of damage: [specify]. Replacement tiles available: [yes/no].",
            'work_category' => 'Tiling / Flooring',
        ],
        'utility_event' => [
            'label' => '🪑 Utility Assistance (Event Chairs/Tables)',
            'nature_of_request' => 'Utility aide assistance — chairs/tables for [event name]',
            'work_summary' => "Request for utility aide/s to assist in setting up and removing chairs and tables for [event name] at [venue/location]. Schedule: [date and time]. Number of chairs/tables: [specify].",
            'work_category' => 'Utility / Manpower Assistance',
        ],
    ];

    $jobRequestTemplatesForJs = collect($jobRequestTemplates)->mapWithKeys(fn ($t, $key) => [
        $key => [
            'nature_of_request' => $t['nature_of_request'],
            'work_summary' => $t['work_summary'],
            'work_category' => $t['work_category'],
        ],
    ]);
@endphp

<div class="mt-6 border border-dashed rounded-lg p-4 bg-gray-50">
    <label class="block mb-2 font-semibold text-sm text-gray-600">💡 Suggest wording for (optional)</label>

    <div class="flex flex-wrap gap-2 items-center">
        <select id="jobRequestTemplatePicker" class="border rounded-lg p-3">
            <option value="">— Select type of work —</option>
            @foreach($jobRequestTemplates as $key => $t)
                <option value="{{ $key }}">{{ $t['label'] }}</option>
            @endforeach
        </select>

        <button type="button" onclick="applyJobRequestTemplate()"
            class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm font-semibold">
            Fill Nature of Request / Work Summary / Work Category
        </button>
    </div>

    <p class="text-xs text-gray-400 mt-2">Fills in the fields below with common wording for that type of work — edit the bracketed parts to match this request.</p>
</div>

<script>
    const jobRequestTemplates = @json($jobRequestTemplatesForJs);

    function applyJobRequestTemplate() {
        const key = document.getElementById('jobRequestTemplatePicker').value;
        if (!key || !jobRequestTemplates[key]) return;

        const nature = document.querySelector('input[name="nature_of_request"]');
        const summary = document.querySelector('textarea[name="work_summary"]');
        const category = document.querySelector('input[name="work_category"]');

        const hasExisting = nature.value.trim() || summary.value.trim() || category.value.trim();

        if (!hasExisting) {
            fillJobRequestTemplate(key);
            return;
        }

        Swal.fire({
            icon: 'question',
            iconColor: '#a855f7',
            title: 'Replace current wording?',
            html: 'This will replace the current text in <strong>Nature of Request</strong>, <strong>Work Summary</strong>, and <strong>Work Category</strong>.',
            showCancelButton: true,
            confirmButtonText: 'Yes, replace it 💜',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#a855f7',
            cancelButtonColor: '#6b7280',
            background: 'linear-gradient(160deg, #fff, #faf5ff)',
        }).then((result) => {
            if (result.isConfirmed) {
                fillJobRequestTemplate(key);
            }
        });
    }

    function fillJobRequestTemplate(key) {
        const t = jobRequestTemplates[key];
        document.querySelector('input[name="nature_of_request"]').value = t.nature_of_request;
        document.querySelector('textarea[name="work_summary"]').value = t.work_summary;
        document.querySelector('input[name="work_category"]').value = t.work_category;
    }
</script>

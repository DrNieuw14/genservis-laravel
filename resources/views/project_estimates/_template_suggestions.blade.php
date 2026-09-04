@php
    $estimateTemplates = [
        'electrical' => [
            'label' => '⚡ Electrical Work',
            'scope_of_work' => "Inspection, troubleshooting, and repair/replacement of electrical wiring, outlets, switches, circuit breakers, and lighting fixtures within the specified area. Includes testing of affected circuits after work completion.",
            'assumptions' => "Existing electrical panel and main power supply are in good working condition. Work area will be cleared of furniture and obstructions prior to the start of work.",
            'exclusions' => "Excludes upgrade of the main electrical panel/service entrance, generator/UPS work, and wiring outside the specified area. Excludes permit and utility company fees, if applicable.",
        ],
        'plumbing' => [
            'label' => '🔧 Plumbing Work',
            'scope_of_work' => "Inspection, repair, and/or replacement of pipes, fittings, faucets, valves, and fixtures (e.g., toilets, sinks, drains) within the specified area, including leak testing after work.",
            'assumptions' => "Main water supply and drainage lines outside the work area are in good condition. Water supply can be shut off temporarily during work hours as needed.",
            'exclusions' => "Excludes septic tank/sewage line work beyond the immediate fixture connections, deep excavation, and repiping of the building's main supply lines. Excludes permit fees, if applicable.",
        ],
        'painting' => [
            'label' => '🎨 Painting Work',
            'scope_of_work' => "Surface preparation (cleaning, scraping, sanding, patching minor cracks/holes), masking of fixtures, and application of primer and finish coats of paint on the specified surfaces.",
            'assumptions' => "Area to be painted is accessible and free of heavy furniture/equipment during the work period. Existing wall/ceiling surfaces do not require major structural repair.",
            'exclusions' => "Excludes major wall/ceiling repair (e.g., re-plastering, waterproofing), removal/reinstallation of fixed furniture and equipment, and repainting of areas not specified in the scope.",
        ],
        'tiling' => [
            'label' => '🧱 Retiling / Floor Work',
            'scope_of_work' => "Removal of damaged/old tiles, surface preparation, and installation of new floor/wall tiles including grouting and cleanup within the specified area.",
            'assumptions' => "Subfloor/substrate is level and structurally sound. Replacement tiles matching the existing pattern, or an approved alternative, are available.",
            'exclusions' => "Excludes waterproofing membrane installation, subfloor leveling/repair beyond minor patching, and plumbing/fixture relocation.",
        ],
    ];

    $estimateTemplatesForJs = collect($estimateTemplates)->mapWithKeys(fn ($t, $key) => [
        $key => [
            'scope_of_work' => $t['scope_of_work'],
            'assumptions' => $t['assumptions'],
            'exclusions' => $t['exclusions'],
        ],
    ]);
@endphp

<div class="mt-6 border border-dashed rounded-lg p-4 bg-gray-50">
    <label class="block mb-2 font-semibold text-sm text-gray-600">💡 Suggest wording for (optional)</label>

    <div class="flex flex-wrap gap-2 items-center">
        <select id="estimateTemplatePicker" class="border rounded-lg p-3">
            <option value="">— Select type of work —</option>
            @foreach($estimateTemplates as $key => $t)
                <option value="{{ $key }}">{{ $t['label'] }}</option>
            @endforeach
        </select>

        <button type="button" onclick="applyEstimateTemplate()"
            class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm font-semibold">
            Fill Scope / Assumptions / Exclusions
        </button>
    </div>

    <p class="text-xs text-gray-400 mt-2">Fills in the fields below with common wording for that type of work — edit freely afterward.</p>
</div>

<script>
    const estimateTemplates = @json($estimateTemplatesForJs);

    function applyEstimateTemplate() {
        const key = document.getElementById('estimateTemplatePicker').value;
        if (!key || !estimateTemplates[key]) return;

        const scope = document.querySelector('textarea[name="scope_of_work"]');
        const assumptions = document.querySelector('textarea[name="assumptions"]');
        const exclusions = document.querySelector('textarea[name="exclusions"]');

        const hasExisting = scope.value.trim() || assumptions.value.trim() || exclusions.value.trim();

        if (!hasExisting) {
            fillEstimateTemplate(key);
            return;
        }

        Swal.fire({
            icon: 'question',
            iconColor: '#a855f7',
            title: 'Replace current wording?',
            html: 'This will replace the current text in <strong>Scope of Work</strong>, <strong>Assumptions</strong>, and <strong>Exclusions</strong>.',
            showCancelButton: true,
            confirmButtonText: 'Yes, replace it',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#a855f7',
            cancelButtonColor: '#6b7280',
            background: 'linear-gradient(160deg, #fff, #faf5ff)',
        }).then((result) => {
            if (result.isConfirmed) {
                fillEstimateTemplate(key);
            }
        });
    }

    function fillEstimateTemplate(key) {
        const t = estimateTemplates[key];
        document.querySelector('textarea[name="scope_of_work"]').value = t.scope_of_work;
        document.querySelector('textarea[name="assumptions"]').value = t.assumptions;
        document.querySelector('textarea[name="exclusions"]').value = t.exclusions;
    }
</script>

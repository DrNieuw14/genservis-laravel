@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🏷 Bulk-Tag Items by PPA
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $plan->plan_number }} — tag many items to the same PPA/MFO at once, for PRE budget-ceiling reconciliation.
            </p>

        </div>

        <x-back-button :href="route('procurement.plans.show', $plan->id)" />

    </div>

@if(session('success'))
<div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-lg">
{{ session('success') }}
</div>
@endif

    <!-- Progress summary -->
    @php
        $total = $items->count();
        $tagged = $items->whereNotNull('ppa')->count();
    @endphp

    <div class="bg-indigo-50 rounded-xl p-4 mb-6 flex items-center justify-between">
        <p class="text-indigo-800 font-semibold">
            {{ $tagged }} of {{ $total }} items tagged
        </p>
        <div class="w-1/2 bg-indigo-200 rounded-full h-3">
            <div class="bg-indigo-600 h-3 rounded-full" style="width: {{ $total > 0 ? round($tagged / $total * 100) : 0 }}%"></div>
        </div>
    </div>

    <!-- Filter -->
    <div class="flex flex-col md:flex-row gap-3 mb-4">

        <input
            type="text"
            id="itemSearch"
            placeholder="Filter by material name or UACS code..."
            class="border rounded px-4 py-2 flex-1"
            onkeyup="filterItems()">

        <select id="ppaFilter" class="border rounded px-4 py-2" onchange="filterItems()">
            <option value="">All Items</option>
            <option value="untagged">Untagged Only</option>
            <option value="GASS">Tagged: GASS</option>
            <option value="STO">Tagged: STO</option>
            <option value="MFO1">Tagged: MFO1</option>
            <option value="MFO2">Tagged: MFO2</option>
            <option value="MFO3">Tagged: MFO3</option>
            <option value="MFO4">Tagged: MFO4</option>
        </select>

    </div>

    <!-- Bulk action bar (hidden until something is checked) -->
    <form id="bulkTagForm" method="POST" action="{{ route('procurement.plans.items.bulk-tag-ppa', $plan->id) }}">
        @csrf

        <div
            id="bulkActionBar"
            class="hidden sticky top-2 z-10 bg-indigo-600 text-white rounded-xl shadow-lg p-4 mb-4 flex flex-wrap items-center gap-3">

            <span id="selectedCount" class="font-semibold">0 selected</span>

            <select id="bulkPpaSelect" class="text-gray-800 rounded px-3 py-2">
                <option value="">-- Choose PPA --</option>
                <option value="GASS">GASS — General Administration Support Services</option>
                <option value="STO">STO — Support to Operations</option>
                <option value="MFO1">MFO1 — Higher Education</option>
                <option value="MFO2">MFO2 — Advanced Education</option>
                <option value="MFO3">MFO3 — Research Services</option>
                <option value="MFO4">MFO4 — Technical Advisory Extension Services</option>
            </select>

            <button
                type="button"
                onclick="submitBulkTag()"
                class="bg-white text-indigo-700 font-semibold px-4 py-2 rounded hover:bg-indigo-50">

                Apply to Selected

            </button>

            <button
                type="button"
                onclick="clearSelection()"
                class="text-indigo-100 underline text-sm">

                Clear selection

            </button>

        </div>

        <div id="bulkItemIdsContainer"></div>

    </form>

    <div class="overflow-x-auto">

    <table class="min-w-full text-sm" id="itemsTable">

        <thead>
            <tr class="border-b bg-gray-100">
                <th class="px-3 py-2"><input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)"></th>
                <th class="text-left px-3 py-2">Material</th>
                <th class="text-left px-3 py-2">UACS</th>
                <th class="text-right px-3 py-2">Annual Cost</th>
                <th class="text-left px-3 py-2">Current PPA</th>
            </tr>
        </thead>

        <tbody>

            @foreach($items as $item)

            <tr class="border-b item-row"
                data-name="{{ strtolower($item->material_name) }}"
                data-uacs="{{ strtolower(optional(optional($item->material)->classification)->uacs_code ?? '') }}"
                data-ppa="{{ $item->ppa ?? 'untagged' }}">

                <td class="px-3 py-2">
                    <input type="checkbox" class="item-checkbox" value="{{ $item->id }}" onchange="updateBulkBar()">
                </td>

                <td class="px-3 py-2">{{ $item->material_name }}</td>

                <td class="px-3 py-2 text-gray-500">
                    {{ optional(optional($item->material)->classification)->uacs_code ?? '—' }}
                </td>

                <td class="px-3 py-2 text-right">₱ {{ number_format($item->annual_cost, 2) }}</td>

                <td class="px-3 py-2">
                    @if($item->ppa)
                    <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full text-xs font-semibold">{{ $item->ppa }}</span>
                    @else
                    <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-xs">Untagged</span>
                    @endif
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    </div>

</div>

<script>

    function itemCheckboxes()
    {
        return Array.from(document.querySelectorAll('.item-checkbox'))
            .filter(cb => cb.closest('tr').style.display !== 'none');
    }

    function updateBulkBar()
    {
        const checked = itemCheckboxes().filter(cb => cb.checked);
        const bar = document.getElementById('bulkActionBar');

        if (checked.length > 0) {
            bar.classList.remove('hidden');
            document.getElementById('selectedCount').innerText = checked.length + ' selected';
        } else {
            bar.classList.add('hidden');
        }
    }

    function toggleSelectAll(checkbox)
    {
        itemCheckboxes().forEach(cb => cb.checked = checkbox.checked);
        updateBulkBar();
    }

    function clearSelection()
    {
        itemCheckboxes().forEach(cb => cb.checked = false);
        document.getElementById('selectAllCheckbox').checked = false;
        updateBulkBar();
    }

    function submitBulkTag()
    {
        const checked = itemCheckboxes().filter(cb => cb.checked);
        const ppa = document.getElementById('bulkPpaSelect').value;

        if (checked.length === 0) {
            return;
        }

        if (!ppa) {
            Swal.fire('Pick a PPA first', '', 'warning');
            return;
        }

        Swal.fire({
            title: `Tag ${checked.length} item(s) as ${ppa}?`,
            text: 'This overwrites any existing PPA tag on the selected items.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Apply',
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }

            const container = document.getElementById('bulkItemIdsContainer');
            container.innerHTML = '';

            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'item_ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });

            const ppaInput = document.createElement('input');
            ppaInput.type = 'hidden';
            ppaInput.name = 'ppa';
            ppaInput.value = ppa;
            container.appendChild(ppaInput);

            document.getElementById('bulkTagForm').submit();

        });
    }

    function filterItems()
    {
        const search = document.getElementById('itemSearch').value.toLowerCase();
        const ppaFilter = document.getElementById('ppaFilter').value;

        document.querySelectorAll('.item-row').forEach(row => {

            const matchesSearch = !search
                || row.dataset.name.includes(search)
                || row.dataset.uacs.includes(search);

            const matchesPpa = !ppaFilter || row.dataset.ppa === ppaFilter;

            row.style.display = (matchesSearch && matchesPpa) ? '' : 'none';

        });

        updateBulkBar();
    }

</script>

@endsection

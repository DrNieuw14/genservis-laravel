<!-- Procurement Items -->
 
<div class="bg-white rounded-xl shadow mt-6">

        <div class="p-6 border-b">

            <h2 class="text-xl font-bold">

                Procurement Items

            </h2>

            <p class="text-gray-500">

                Add materials that will be included in this Annual Procurement Plan.

            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Material</th>
                        <th class="px-4 py-3 text-left">Classification</th>
                        <th class="px-4 py-3 text-left">Mode of Procurement</th>
                        <th class="px-4 py-3 text-left">Added By</th>
                        <th class="px-4 py-3 text-left">Unit</th>
                        <th class="px-4 py-3 text-right">Unit Cost</th>
                        <th class="px-4 py-3 text-center">Q1</th>
                        <th class="px-4 py-3 text-center">Q2</th>
                        <th class="px-4 py-3 text-center">Q3</th>
                        <th class="px-4 py-3 text-center">Q4</th>
                        <th class="px-4 py-3 text-center">Annual Qty</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-center">Action</th>
                        <th class="px-4 py-3 text-center">Approval</th>
                    </tr>
                </thead>
            
            <tbody>

                 @forelse($plan->items as $item)

                <tr class="border-b hover:bg-gray-50">

                    <td class="px-4 py-3">
                        {{ $item->material_name }}
                    </td>

                    <td class="px-4 py-3 text-sm">
                        @if(optional($item->material)->classification)
                            {{ $item->material->classification->code }} / {{ $item->material->classification->uacs_code }}
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-sm">
                        {{ $item->procurement_method ?? '—' }}
                    </td>

                    <td class="px-4 py-3 text-sm">
                        {{ optional($item->creator)->fullname ?? optional($item->creator)->name ?? '—' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ optional($item->unit)->name }}
                    </td>

                    <td class="px-4 py-3 text-right">
                        ₱ {{ number_format($item->estimated_unit_cost,2) }}
                    </td>

                    <td class="px-4 py-3 text-center">{{ $item->q1 }}</td>
                    <td class="px-4 py-3 text-center">{{ $item->q2 }}</td>
                    <td class="px-4 py-3 text-center">{{ $item->q3 }}</td>
                    <td class="px-4 py-3 text-center">{{ $item->q4 }}</td>

                    <td class="px-4 py-3 text-center">
                        {{ $item->annual_quantity }}
                    </td>

                    <td class="px-4 py-3 text-right">
                        ₱ {{ number_format($item->annual_cost,2) }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        <button
                            type="button"
                            class="text-blue-600 hover:underline"
                            onclick="editProcurementItem({{ $item->id }})">

                            Edit

                        </button>
                        |
                        <form
                            id="deleteForm{{ $item->id }}"
                            action="{{ route('procurement.plans.items.destroy', $item->id) }}"
                            method="POST"
                            class="inline">

                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="reason" id="deleteReason{{ $item->id }}">

                            <button
                                type="button"
                                onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->material_name) }}')"
                                class="text-red-600 hover:underline">

                                Delete

                            </button>

                        </form>
                    </td>

                    <td class="px-4 py-3 text-center">

                        @if(auth()->user()->hasPermission('view-ppmp'))

                            <form
                                action="{{ route('procurement.plans.items.toggle-approval', $item->id) }}"
                                method="POST"
                                class="inline">

                                @csrf

                                @if($item->is_approved)

                                    <button
                                        type="submit"
                                        class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm hover:bg-green-200">

                                        ✔ Approved

                                    </button>

                                @else

                                    <button
                                        type="submit"
                                        class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-sm hover:bg-yellow-200">

                                        ⏳ Pending

                                    </button>

                                @endif

                            </form>

                        @else

                            @if($item->is_approved)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">✔ Approved</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-sm">⏳ Pending</span>
                            @endif

                        @endif

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="14" class="text-center text-gray-500 py-10">
                        No Procurement Items Yet
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

            
        </div>

    </div>
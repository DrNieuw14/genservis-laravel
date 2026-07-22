@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧾 {{ $issuance->slip_no }}
                <span class="text-sm font-semibold px-3 py-1 rounded-full {{ $issuance->isIcs() ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                    {{ $issuance->isIcs() ? 'ICS' : 'PAR' }}
                </span>
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $issuance->formTypeLabel() }}
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="auth()->user()->hasPermission('manage-property-issuance') ? route('property-issuances.index') : route('property-issuances.mine')" />

            <a href="{{ route('property-issuances.print', $issuance->id) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

            @if(auth()->user()->hasPermission('manage-property-issuance'))
                <form method="POST" action="{{ route('property-issuances.destroy', $issuance->id) }}"
                      onsubmit="return confirm('Delete this issuance slip? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                        🗑 Delete
                    </button>
                </form>
            @endif

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <div class="border rounded-lg p-5 bg-gray-50">
            <p class="text-sm text-gray-500 font-semibold">Room</p>
            <p class="text-lg font-semibold text-gray-800">{{ $issuance->room->room_name ?? '— (not room-tracked)' }}</p>

            <p class="text-sm text-gray-500 font-semibold mt-3">Received By</p>
            <p class="text-lg font-semibold text-gray-800">{{ $issuance->recipient->fullname ?? '-' }}</p>
            <p class="text-gray-500">{{ $issuance->recipient->positionRecord->position_name ?? '-' }}</p>

            <p class="text-sm text-gray-500 font-semibold mt-3">Date Issued</p>
            <p class="text-lg font-semibold text-gray-800">{{ $issuance->issued_at->format('M d, Y') }}</p>
        </div>

        <div class="border rounded-lg p-5 bg-gray-50">
            <p class="text-sm text-gray-500 font-semibold">Fund Cluster</p>
            <p class="text-lg font-semibold text-gray-800">{{ $issuance->fund_cluster ?: '-' }}</p>

            @if($issuance->isIcs())
                <p class="text-sm text-gray-500 font-semibold mt-3">P.O. No.</p>
                <p class="text-lg font-semibold text-gray-800">{{ $issuance->po_number ?: '-' }}</p>
            @endif

            <p class="text-sm text-gray-500 font-semibold mt-3">Total Value</p>
            <p class="text-lg font-semibold text-gray-800">₱{{ number_format($issuance->totalValue(), 2) }}</p>
        </div>

    </div>

    @if($issuance->remarks)
        <p class="text-gray-600 mb-6"><strong>Remarks:</strong> {{ $issuance->remarks }}</p>
    @endif

    <h3 class="font-bold text-lg mb-3">Issued Items</h3>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Property Name</th>
                    <th class="p-3 text-left">Property No.</th>
                    <th class="p-3 text-left">Unit</th>
                    <th class="p-3 text-center">Qty</th>
                    <th class="p-3 text-center">Unit Cost (₱)</th>
                    <th class="p-3 text-center">Total (₱)</th>
                    <th class="p-3 text-center">Date Acquired</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach($issuance->items as $item)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $item->property_name }}</td>
                        <td class="p-3">{{ $item->property_number ?? '-' }}</td>
                        <td class="p-3">{{ $item->unit ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $item->quantity }}</td>
                        <td class="p-3 text-center">{{ $item->unit_cost !== null ? number_format($item->unit_cost, 2) : '-' }}</td>
                        <td class="p-3 text-center font-semibold">{{ number_format($item->totalCost(), 2) }}</td>
                        <td class="p-3 text-center">{{ $item->date_acquired?->format('M d, Y') ?? '-' }}</td>
                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <h3 class="font-bold text-lg mt-8 mb-3">Evidence Photos</h3>

    <div class="border rounded-lg p-5 bg-gray-50">

        @if(auth()->user()->hasPermission('manage-property-issuance'))
            <form method="POST" action="{{ route('property-issuances.photos.store', $issuance->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block mb-1 font-semibold text-sm">Photo Of</label>
                        <select name="property_issuance_item_id" class="border rounded-lg p-3">
                            <option value="">General / Not item-specific</option>
                            @foreach($issuance->items as $item)
                                <option value="{{ $item->id }}">{{ $item->property_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block mb-1 font-semibold text-sm">Photo(s)</label>
                        <input type="file" name="photos[]" accept="image/*" multiple class="w-full border rounded-lg p-2 bg-white">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                        ⬆️ Upload
                    </button>
                </div>
            </form>
        @endif

        @if($issuance->photos->isNotEmpty())
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($issuance->photos as $photo)
                    <div class="border rounded-lg overflow-hidden bg-white">
                        <a href="{{ $photo->url }}" target="_blank">
                            <img src="{{ $photo->url }}" alt="Evidence photo" class="w-full h-32 object-cover">
                        </a>
                        <div class="p-2">
                            <p class="text-xs font-semibold text-gray-700 truncate">{{ $photo->item->property_name ?? 'General' }}</p>
                            <div class="text-xs text-gray-500 flex justify-between items-center mt-1">
                                <span>{{ $photo->uploader->fullname ?? $photo->uploader->name ?? '-' }}</span>
                                @if(auth()->user()->hasPermission('manage-property-issuance'))
                                    <form method="POST" action="{{ route('property-issuances.photos.destroy', [$issuance->id, $photo->id]) }}"
                                          onsubmit="return confirm('Remove this photo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">🗑</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm mt-4">No evidence photos yet.</p>
        @endif

    </div>

</div>

@endsection

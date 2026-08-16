@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ✉️ Letter / Document Tracking
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Log and track letters/documents routed through the Campus Administrator for signature.
            </p>
        </div>

        <button type="button" onclick="openLetterModal('add')"
            class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow">
            ➕ Log Letter
        </button>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FILTERS -->
    <form method="GET" action="{{ route('letter-tracking.index') }}" class="border rounded-lg p-5 bg-gray-50 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            <div>
                <label class="block mb-1 font-semibold text-sm">Direction</label>
                <select name="direction" class="w-full border rounded-lg p-3">
                    <option value="">All</option>
                    <option value="incoming" {{ $direction === 'incoming' ? 'selected' : '' }}>📩 Incoming</option>
                    <option value="outgoing" {{ $direction === 'outgoing' ? 'selected' : '' }}>📤 Outgoing</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-sm">Status</label>
                <select name="status" class="w-full border rounded-lg p-3">
                    <option value="">All</option>
                    @foreach(\App\Models\LetterTracking::STATUSES as $key => $label)
                        <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-sm">Search</label>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Control no., subject, office..."
                    class="w-full border rounded-lg p-3">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                    🔍 Filter
                </button>

                @if($direction || $status || $search)
                    <a href="{{ route('letter-tracking.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg shadow">
                        Clear
                    </a>
                @endif
            </div>

        </div>

    </form>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Control No.</th>
                    <th class="p-3 text-left">Direction</th>
                    <th class="p-3 text-left">Subject</th>
                    <th class="p-3 text-left">From → To</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($letters as $letter)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $letter->control_no }}</td>
                        <td class="p-3">{{ $letter->directionLabel() }}</td>
                        <td class="p-3">{{ $letter->subject }}</td>
                        <td class="p-3 text-sm text-gray-600">
                            {{ $letter->from_office ?: '-' }} → {{ $letter->to_office ?: '-' }}
                        </td>
                        <td class="p-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold
                                {{ $letter->status === 'released' ? 'bg-green-100 text-green-700' : ($letter->status === 'for_signature' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ $letter->statusLabel() }}
                            </span>
                            @if($letter->currentStatusDate())
                                <p class="text-xs text-gray-400 mt-1">{{ $letter->currentStatusDate()->format('M d, Y') }}</p>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex gap-2 justify-center flex-wrap">

                                @if($letter->nextStatus())
                                    <form method="POST" action="{{ route('letter-tracking.advance', $letter->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-blue-600 hover:underline text-sm">
                                            {{ [
                                                'for_signature' => '➡️ Forward for Signature',
                                                'signed' => '✅ Mark Signed',
                                                'released' => '📤 Mark Released',
                                            ][$letter->nextStatus()] }}
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="text-gray-600 hover:underline text-sm"
                                    onclick='openLetterModal("edit", {{ $letter->id }}, {{ json_encode($letter->direction) }}, {{ json_encode($letter->subject) }}, {{ json_encode($letter->from_office) }}, {{ json_encode($letter->to_office) }}, {{ json_encode($letter->date_received?->format("Y-m-d")) }}, {{ json_encode($letter->remarks) }})'>
                                    ✏️ Edit
                                </button>

                                <form method="POST" action="{{ route('letter-tracking.destroy', $letter->id) }}"
                                      onsubmit="return genservisConfirm(event, 'Delete this letter record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">🗑 Delete</button>
                                </form>

                            </div>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            No letters logged yet. Click "Log Letter" to start tracking one.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $letters->links() }}
    </div>

</div>

<!-- ADD/EDIT LETTER MODAL -->
<div id="letterModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 id="letterModalTitle" class="text-xl font-bold">Log Letter</h2>
            <button type="button" onclick="closeLetterModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>
        <form id="letterForm" method="POST" action="{{ route('letter-tracking.store') }}">
            @csrf
            <input type="hidden" id="letterFormMethod" name="_method" value="POST">

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="md:col-span-2">
                    <label class="block mb-1 font-semibold text-sm">Direction</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="direction" id="letterDirectionIncoming" value="incoming" required>
                            📩 Incoming
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="direction" id="letterDirectionOutgoing" value="outgoing" required>
                            📤 Outgoing
                        </label>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-1 font-semibold text-sm">Subject</label>
                    <input type="text" name="subject" id="letterSubject" class="w-full border rounded-lg p-3" required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">From (office/party)</label>
                    <input type="text" name="from_office" id="letterFromOffice" class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">To (office/addressee)</label>
                    <input type="text" name="to_office" id="letterToOffice" class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Date Received (optional)</label>
                    <input type="date" name="date_received" id="letterDateReceived" class="w-full border rounded-lg p-3">
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-1 font-semibold text-sm">Remarks (optional)</label>
                    <textarea name="remarks" id="letterRemarks" rows="2" class="w-full border rounded-lg p-3"></textarea>
                </div>

            </div>

            <div class="border-t px-6 py-4 flex justify-end gap-2">
                <button type="button" onclick="closeLetterModal()" class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow">💾 Save</button>
            </div>

        </form>
    </div>
</div>

<script>

    function openLetterModal(mode, id, direction, subject, fromOffice, toOffice, dateReceived, remarks) {

        document.getElementById('letterModalTitle').innerText = mode === 'edit' ? 'Edit Letter' : 'Log Letter';

        document.getElementById('letterDirectionIncoming').checked = direction === 'incoming';
        document.getElementById('letterDirectionOutgoing').checked = direction === 'outgoing';
        document.getElementById('letterSubject').value = subject ?? '';
        document.getElementById('letterFromOffice').value = fromOffice ?? '';
        document.getElementById('letterToOffice').value = toOffice ?? '';
        document.getElementById('letterDateReceived').value = dateReceived ?? '';
        document.getElementById('letterRemarks').value = remarks ?? '';

        const form = document.getElementById('letterForm');

        if (mode === 'edit') {
            form.action = '{{ url('/letter-tracking') }}/' + id;
            document.getElementById('letterFormMethod').value = 'PUT';
        } else {
            form.action = '{{ route('letter-tracking.store') }}';
            document.getElementById('letterFormMethod').value = 'POST';
        }

        document.getElementById('letterModal').classList.remove('hidden');
    }

    function closeLetterModal() {
        document.getElementById('letterModal').classList.add('hidden');
    }

</script>

@endsection

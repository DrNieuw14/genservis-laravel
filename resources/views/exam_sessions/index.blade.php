@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📝 Exam Results
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Admission Testing — exam sessions and results, cross-checked against the applicant roster.
            </p>
        </div>

        <button type="button" onclick="openCreateSessionModal()"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow whitespace-nowrap">
            ➕ New Exam Session
        </button>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
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

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Session</th>
                    <th class="p-3 text-left">Admission Year</th>
                    <th class="p-3 text-center">Exam Date</th>
                    <th class="p-3 text-center">Results</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($sessions as $session)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3 font-semibold">{{ $session->label }}</td>

                        <td class="p-3">{{ $session->year->label }}</td>

                        <td class="p-3 text-center">{{ $session->exam_date?->format('M d, Y') ?: '-' }}</td>

                        <td class="p-3 text-center">{{ $session->results_count }}</td>

                        <td class="p-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('exam-sessions.show', $session->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                    View
                                </a>

                                <form method="POST" action="{{ route('exam-sessions.destroy', $session->id) }}"
                                      onsubmit="return confirm('Delete this exam session and all {{ $session->results_count }} of its results? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            No exam sessions yet. Create one to start uploading results.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- CREATE SESSION MODAL -->
<div id="createSessionModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 class="text-xl font-bold">New Exam Session</h2>
            <button type="button" onclick="closeCreateSessionModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>

        <form method="POST" action="{{ route('exam-sessions.store') }}">
            @csrf

            <div class="p-6 space-y-4">

                <div>
                    <label class="block mb-1 font-semibold text-sm">Admission Year</label>
                    <select name="admission_year_id" class="w-full border rounded-lg p-3" required>
                        <option value="">-- Select --</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}">{{ $year->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Label</label>
                    <input type="text" name="label" placeholder="e.g. April 21, 2026" class="w-full border rounded-lg p-3" required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Exam Date (optional)</label>
                    <input type="date" name="exam_date" class="w-full border rounded-lg p-3">
                </div>

            </div>

            <div class="flex justify-end gap-2 border-t px-6 py-4">
                <button type="button" onclick="closeCreateSessionModal()" class="bg-gray-300 hover:bg-gray-400 px-5 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">💾 Create</button>
            </div>

        </form>
    </div>
</div>

<script>
    function openCreateSessionModal() {
        document.getElementById('createSessionModal').classList.remove('hidden');
    }
    function closeCreateSessionModal() {
        document.getElementById('createSessionModal').classList.add('hidden');
    }
    @if($errors->any())
        openCreateSessionModal();
    @endif
</script>

@endsection

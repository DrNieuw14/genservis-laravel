@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🎓 Admission Years
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Admission Testing Services — applicant rosters by admission year.
            </p>
        </div>

        <button type="button" onclick="openCreateYearModal()"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow whitespace-nowrap">
            ➕ New Admission Year
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
                    <th class="p-3 text-left">Label</th>
                    <th class="p-3 text-left">Description</th>
                    <th class="p-3 text-center">Applicants</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($years as $year)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3 font-semibold">{{ $year->label }}</td>

                        <td class="p-3">{{ $year->description ?: '-' }}</td>

                        <td class="p-3 text-center">{{ $year->applicants_count }}</td>

                        <td class="p-3 text-center">
                            @if($year->is_active)
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">Active</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-1 rounded-full">Closed</span>
                            @endif
                        </td>

                        <td class="p-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admission-applicants.index', $year->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                    View Roster
                                </a>

                                <form method="POST" action="{{ route('admission-years.destroy', $year->id) }}"
                                      onsubmit="return confirm('Delete this admission year and its entire applicant roster ({{ $year->applicants_count }} records)? This cannot be undone.')">
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
                            No admission years yet. Create one to start uploading a roster.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- CREATE YEAR MODAL -->
<div id="createYearModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 class="text-xl font-bold">New Admission Year</h2>
            <button type="button" onclick="closeCreateYearModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>

        <form method="POST" action="{{ route('admission-years.store') }}">
            @csrf

            <div class="p-6 space-y-4">

                <div>
                    <label class="block mb-1 font-semibold text-sm">Label</label>
                    <input type="text" name="label" placeholder="e.g. AY 2026-2027" class="w-full border rounded-lg p-3" required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Description (optional)</label>
                    <textarea name="description" rows="2" class="w-full border rounded-lg p-3"></textarea>
                </div>

            </div>

            <div class="flex justify-end gap-2 border-t px-6 py-4">
                <button type="button" onclick="closeCreateYearModal()" class="bg-gray-300 hover:bg-gray-400 px-5 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">💾 Create</button>
            </div>

        </form>
    </div>
</div>

<script>
    function openCreateYearModal() {
        document.getElementById('createYearModal').classList.remove('hidden');
    }
    function closeCreateYearModal() {
        document.getElementById('createYearModal').classList.add('hidden');
    }
    @if($errors->any())
        openCreateYearModal();
    @endif
</script>

@endsection

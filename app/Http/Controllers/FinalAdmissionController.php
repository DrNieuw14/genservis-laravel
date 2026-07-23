<?php

namespace App\Http\Controllers;

use App\Models\AdmissionApplicant;
use App\Models\AdmissionYear;
use App\Models\FinalAdmission;
use App\Models\ProgramQuota;
use App\Models\ProgramRanking;
use App\Models\Reapplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinalAdmissionController extends Controller
{
    public function index(Request $request)
    {
        $years = AdmissionYear::orderByDesc('id')->get();

        $selectedYearId = $request->input('admission_year_id', $years->first()?->id);
        $selectedYear = $years->firstWhere('id', (int) $selectedYearId);

        $programCode = $request->input('program');
        $search = $request->input('search');
        $reappSearch = $request->input('reapp_search');

        $programCounts = collect();
        $searchResults = collect();
        $finalList = collect();
        $quotaStatus = null;
        $passedList = collect();
        $reapplicantsList = collect();

        if ($selectedYear) {
            $programCounts = FinalAdmission::where('admission_year_id', $selectedYear->id)
                ->get()
                ->groupBy('program_code')
                ->map(fn ($group, $code) => [
                    'code' => $code,
                    'program' => $group->first()->program_name,
                    'count' => $group->count(),
                ])
                ->sortByDesc('count')
                ->values();

            if ($programCode) {
                $quotaStatus = $this->quotaStatusForProgram($selectedYear->id, $programCode);

                $finalList = FinalAdmission::where('admission_year_id', $selectedYear->id)
                    ->where('program_code', $programCode)
                    ->with(['applicant', 'addedBy'])
                    ->get();

                $this->attachExamInfo($finalList, 'applicant');

                // Same "does a real Reapplication naming this program exist
                // for them" signal used to split the printed list — shown
                // here as a per-row Source column so it's visible on screen
                // too, not just on the printout.
                $reapplicantIdsForThisProgram = $this->reapplicantApplicantIdsForProgram($selectedYear->id, $programCode);

                $finalList->each(function ($entry) use ($reapplicantIdsForThisProgram) {
                    $entry->fromReapplication = in_array($entry->admission_applicant_id, $reapplicantIdsForThisProgram);
                });

                $existingFinalByApplicant = FinalAdmission::where('admission_year_id', $selectedYear->id)
                    ->get()
                    ->keyBy('admission_applicant_id');

                $passedList = $this->passedListForProgram($selectedYear->id, $programCode)
                    ->map(function ($ranking) use ($existingFinalByApplicant, $programCode) {
                        $existing = $ranking->admission_applicant_id ? $existingFinalByApplicant->get($ranking->admission_applicant_id) : null;

                        $ranking->alreadyFinalizedHere = $existing && $existing->program_code === $programCode;
                        $ranking->alreadyFinalizedElsewhere = $existing && $existing->program_code !== $programCode;

                        return $ranking;
                    });

                // Reapplicants Waiting List — students who didn't make their
                // ORIGINAL program's cutoff and picked this program as their
                // 1st or 2nd choice instead. Waiting, not automatically
                // admitted here — staff decide whether/who to promote onto
                // the Final List, same as "Who Passed".
                $reapplicantsList = $this->reapplicantsForProgram($selectedYear->id, $programCode, $reappSearch)
                    ->map(function ($reapp) use ($existingFinalByApplicant, $programCode) {
                        $existing = $reapp->admission_applicant_id ? $existingFinalByApplicant->get($reapp->admission_applicant_id) : null;

                        $reapp->alreadyFinalizedHere = $existing && $existing->program_code === $programCode;
                        $reapp->alreadyFinalizedElsewhere = $existing && $existing->program_code !== $programCode;

                        return $reapp;
                    });
            }

            // Search works from "All Programs" too, not just once a specific
            // program is selected — each result gets its own program picker
            // next to Add, so staff can search once and place different
            // people into different programs without re-selecting the
            // top-level filter every time.
            if ($search) {
                $searchResults = AdmissionApplicant::where('admission_year_id', $selectedYear->id)
                    ->where(fn ($q) => $q->where('given_name', 'like', "%{$search}%")
                        ->orWhere('family_name', 'like', "%{$search}%")
                        ->orWhere('control_number', 'like', "%{$search}%"))
                    ->with('finalAdmission')
                    // An exact Control Number match always sorts first — on
                    // a 5,500-row roster a short/common search term (e.g.
                    // searching the un-padded number "39") can partial-match
                    // dozens of other rows (139, 239, 391...), and without
                    // this the LIMIT 25 could cut off before ever reaching
                    // the actual exact match.
                    ->orderByRaw('control_number = ? DESC', [$search])
                    ->limit(25)
                    ->get();

                $this->attachExamInfo($searchResults);
            }
        }

        $programOptions = ProgramRanking::PROGRAM_SHORT_CODES;

        return view('final_admissions.index', compact(
            'years', 'selectedYear', 'programCode', 'programOptions', 'programCounts',
            'search', 'searchResults', 'finalList', 'quotaStatus', 'passedList', 'reapplicantsList', 'reappSearch'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admission_year_id' => 'required|exists:admission_years,id',
            'admission_applicant_id' => 'required|exists:admission_applicants,id',
            'program_code' => 'required|string',
        ]);

        $programName = array_flip(ProgramRanking::PROGRAM_SHORT_CODES)[$validated['program_code']] ?? $validated['program_code'];

        $existing = FinalAdmission::where('admission_year_id', $validated['admission_year_id'])
            ->where('admission_applicant_id', $validated['admission_applicant_id'])
            ->first();

        if ($existing && $existing->program_code === $validated['program_code']) {
            return back()->with('error', 'This applicant is already on the final list for this program.');
        }

        if ($existing) {
            return back()->with('error', "This applicant is already finalized to {$existing->program_code}. Remove them from there first, or use Move.")
                ->with('moveCandidate', $existing->id)
                ->with('moveTargetProgram', $validated['program_code']);
        }

        FinalAdmission::create([
            'admission_year_id' => $validated['admission_year_id'],
            'program_code' => $validated['program_code'],
            'program_name' => $programName,
            'admission_applicant_id' => $validated['admission_applicant_id'],
            'added_by' => Auth::id(),
        ]);

        return redirect()
            ->route('final-admissions.index', ['admission_year_id' => $validated['admission_year_id'], 'program' => $validated['program_code']])
            ->with('success', 'Applicant added to the final list.');
    }

    // Bulk version of store() — for adding an entire (checked subset of a)
    // "Who Passed" list at once instead of one Add click per row. Applicants
    // already finalized to ANY program (this one or elsewhere) are silently
    // skipped rather than erroring the whole batch out — a bulk action
    // shouldn't fail entirely just because one of many rows was already
    // handled; the summary message says how many were actually skipped.
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'admission_year_id' => 'required|exists:admission_years,id',
            'program_code' => 'required|string',
            'admission_applicant_ids' => 'required|array|min:1',
            'admission_applicant_ids.*' => 'exists:admission_applicants,id',
        ], [
            'admission_applicant_ids.required' => 'Select at least one applicant to add.',
        ]);

        $programName = array_flip(ProgramRanking::PROGRAM_SHORT_CODES)[$validated['program_code']] ?? $validated['program_code'];

        $alreadyFinalized = FinalAdmission::where('admission_year_id', $validated['admission_year_id'])
            ->whereIn('admission_applicant_id', $validated['admission_applicant_ids'])
            ->pluck('admission_applicant_id')
            ->all();

        $toAdd = array_diff($validated['admission_applicant_ids'], $alreadyFinalized);

        foreach ($toAdd as $applicantId) {
            FinalAdmission::create([
                'admission_year_id' => $validated['admission_year_id'],
                'program_code' => $validated['program_code'],
                'program_name' => $programName,
                'admission_applicant_id' => $applicantId,
                'added_by' => Auth::id(),
            ]);
        }

        $message = count($toAdd) . ' applicant(s) added to the final list.';

        if (!empty($alreadyFinalized)) {
            $message .= ' ' . count($alreadyFinalized) . ' were skipped (already finalized).';
        }

        return redirect()
            ->route('final-admissions.index', ['admission_year_id' => $validated['admission_year_id'], 'program' => $validated['program_code']])
            ->with('success', $message);
    }

    // Deliberate applicant-initiated correction — moves an existing final
    // admission to a different program rather than requiring a separate
    // remove-then-re-add, since "already finalized elsewhere" is a real,
    // expected scenario (a student reconsidered, or staff are correcting
    // an earlier placement), not an error state to dead-end on.
    public function move(Request $request, $id)
    {
        $finalAdmission = FinalAdmission::findOrFail($id);

        $validated = $request->validate([
            'program_code' => 'required|string',
        ]);

        $programName = array_flip(ProgramRanking::PROGRAM_SHORT_CODES)[$validated['program_code']] ?? $validated['program_code'];

        $finalAdmission->update([
            'program_code' => $validated['program_code'],
            'program_name' => $programName,
            'added_by' => Auth::id(),
        ]);

        return redirect()
            ->route('final-admissions.index', ['admission_year_id' => $finalAdmission->admission_year_id, 'program' => $validated['program_code']])
            ->with('success', 'Applicant moved to the new program.');
    }

    public function destroy($id)
    {
        $finalAdmission = FinalAdmission::findOrFail($id);
        $yearId = $finalAdmission->admission_year_id;
        $programCode = $finalAdmission->program_code;

        $finalAdmission->delete();

        return redirect()
            ->route('final-admissions.index', ['admission_year_id' => $yearId, 'program' => $programCode])
            ->with('success', 'Removed from the final list.');
    }

    // Prints the official, staff-curated Final List — grouped by program,
    // same letterhead/table convention as Program Rankings' Admitted
    // Report. An optional ?program= scopes it to just one program, same
    // as that report's per-program print.
    public function print(Request $request, $yearId)
    {
        $year = AdmissionYear::findOrFail($yearId);
        $onlyProgramCode = $request->query('program');

        $groups = FinalAdmission::where('admission_year_id', $year->id)
            ->when($onlyProgramCode, fn ($q) => $q->where('program_code', $onlyProgramCode))
            ->with('applicant')
            ->get()
            ->groupBy('program_code')
            ->map(function ($entries, $code) {
                $this->attachExamInfo($entries, 'applicant');

                // Split into who's here on their own program's cutoff vs who
                // came in via the Reapplicants Waiting List (a real
                // Reapplication record naming this program as their 1st/2nd
                // choice exists for them) — the official document needs to
                // show these as two separate tables, not one list that
                // silently mixes direct passers with reapplicant transfers.
                $reapplicantApplicantIds = $this->reapplicantApplicantIdsForProgram($entries->first()->admission_year_id, $code);

                $sorted = $entries->sortBy(fn ($e) => $e->applicant?->fullName() ?? '')->values();

                return [
                    'code' => $code,
                    'program' => $entries->first()->program_name,
                    'direct' => $sorted->reject(fn ($e) => in_array($e->admission_applicant_id, $reapplicantApplicantIds))->values(),
                    'viaReapplication' => $sorted->filter(fn ($e) => in_array($e->admission_applicant_id, $reapplicantApplicantIds))->values(),
                ];
            })
            ->sortBy('code')
            ->values();

        return view('final_admissions.print', compact('year', 'groups'));
    }

    // Every admission_applicant_id that has a real Reapplication naming
    // this program (by short code) as their 1st or 2nd choice — used to
    // split the printed Final List into "direct" vs "via reapplication".
    private function reapplicantApplicantIdsForProgram(int $yearId, string $programCode): array
    {
        $choiceText = Reapplication::choiceTextForProgramCode($programCode);

        if (!$choiceText) {
            return [];
        }

        return Reapplication::where('admission_year_id', $yearId)
            ->where(fn ($q) => $q->whereRaw('UPPER(first_choice) = ?', [$choiceText])
                ->orWhereRaw('UPPER(second_choice) = ?', [$choiceText]))
            ->whereNotNull('admission_applicant_id')
            ->pluck('admission_applicant_id')
            ->all();
    }

    private function quotaStatusForProgram(int $yearId, string $programCode): ?array
    {
        $quota = ProgramQuota::where('admission_year_id', $yearId)
            ->where('program_code', $programCode)
            ->first();

        if (!$quota || !$quota->isSet()) {
            return null;
        }

        return ['quota' => $quota->quota()];
    }

    /**
     * Who actually PASSED per Program Rankings' own cutoff — same
     * tie-inclusive logic as ProgramRankingController::applyAdmissionStatus()
     * (everyone tied with the Nth-ranked Grade gets in too). Returns an
     * empty collection, not null, when no quota is set — nothing has
     * "passed" yet if no cutoff line has been drawn.
     */
    private function passedListForProgram(int $yearId, string $programCode): \Illuminate\Support\Collection
    {
        $quota = ProgramQuota::where('admission_year_id', $yearId)
            ->where('program_code', $programCode)
            ->first();

        if (!$quota || !$quota->isSet()) {
            return collect();
        }

        $rankings = ProgramRanking::where('admission_year_id', $yearId)
            ->get()
            ->filter(fn ($r) => $r->shortProgramCode() === $programCode)
            ->sortByDesc('grade')
            ->values();

        $total = $rankings->count();
        $quotaValue = $quota->quota();

        if ($quotaValue >= $total) {
            return $rankings;
        }

        $cutoffGrade = $rankings->get($quotaValue - 1)?->grade;

        return $rankings->filter(fn ($r) => $r->grade !== null && $r->grade >= $cutoffGrade)->values();
    }

    /**
     * Reapplicants who picked this program as their 1st or 2nd choice —
     * same matching convention as ReapplicationController's own program
     * filter (Reapplication::choiceTextForProgramCode() reverses the short
     * code to this form's exact wording, then matches case-insensitively
     * since the sheet's Title Case doesn't line up with PROGRAM_SHORT_CODES'
     * uppercase keys). Ranked by exam Grade descending, same as everywhere
     * else a "who's ahead" ordering matters.
     */
    private function reapplicantsForProgram(int $yearId, string $programCode, ?string $search = null): \Illuminate\Support\Collection
    {
        $choiceText = Reapplication::choiceTextForProgramCode($programCode);

        if (!$choiceText) {
            return collect();
        }

        $reapplicants = Reapplication::where('admission_year_id', $yearId)
            ->where(fn ($q) => $q->whereRaw('UPPER(first_choice) = ?', [$choiceText])
                ->orWhereRaw('UPPER(second_choice) = ?', [$choiceText]))
            ->when($search, fn ($q) => $q->where(fn ($sub) => $sub
                ->where('surname', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('control_number', 'like', "%{$search}%")))
            ->with('applicant')
            ->get();

        $this->attachExamInfo($reapplicants, 'applicant');

        return $reapplicants->sortByDesc(fn ($r) => $r->examGrade ?? -1)->values();
    }

    // Same cross-reference as Reapplication::attachExamInfo() — pulls each
    // row's actual exam Score/Grade so staff can see performance context
    // right where they're deciding who to finalize, not just a bare name.
    // $relation lets this run against either AdmissionApplicant rows
    // directly (search results) or rows that hold the applicant via a
    // relation (FinalAdmission::applicant).
    private function attachExamInfo(iterable $rows, ?string $relation = null): void
    {
        $applicantIds = [];

        foreach ($rows as $row) {
            $applicant = $relation ? $row->{$relation} : $row;
            if ($applicant) {
                $applicantIds[] = $applicant->id;
            }
        }

        $applicantIds = array_unique($applicantIds);

        if (empty($applicantIds)) {
            return;
        }

        $ownRankings = ProgramRanking::whereIn('admission_applicant_id', $applicantIds)
            ->get()
            ->keyBy('admission_applicant_id');

        foreach ($rows as $row) {
            $applicant = $relation ? $row->{$relation} : $row;
            $own = $applicant ? $ownRankings->get($applicant->id) : null;

            $row->examScore = $own?->raw_score;
            $row->examGrade = $own?->grade;
            $row->examProgramCode = $own?->shortProgramCode();
        }
    }
}

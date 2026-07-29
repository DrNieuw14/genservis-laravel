<?php

namespace Database\Seeders;

use App\Models\ClassSchedule;
use App\Models\Program;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ClassScheduleImportSeeder extends Seeder
{
    /**
     * Real FY2026-2027 First Semester class schedule, transcribed
     * programmatically (not by eye) from Other docs/Schedule-First-Semester-
     * 2026-2027.xls via a Python parser — see project memory for the parsing
     * approach and verification (gap-based run detection + content-based
     * instructor/room role assignment, since room names vary too much to
     * pattern-match reliably but instructor names are always MR/MS-prefixed).
     *
     * 900 entries across 118 sections in the 10 real degree programs.
     * Instructor names are transcribed as plain text (`faculty_name`) — only
     * title + surname is available (no first name, no employee ID), so
     * auto-matching against real `Personnel` records risked linking the
     * wrong actual person. `personnel_id` stays null for every imported row;
     * Joseph can link real Personnel records later through the live UI once
     * he confirms who's who.
     */
    public function run(): void
    {
        $path = storage_path('app/schedule_import_2026_2027.json');

        if (! file_exists($path)) {
            $this->command?->error('schedule_import_2026_2027.json not found in storage/app.');
            return;
        }

        $rows = json_decode(file_get_contents($path), true);

        $programCache = Program::pluck('id', 'code')->toArray();
        $sectionCache = [];
        $subjectCache = Subject::pluck('id', 'code')->toArray();

        $created = 0;
        $skippedNoProgram = [];

        foreach ($rows as $row) {

            $programCode = $row['sheet'];

            if (! isset($programCache[$programCode])) {
                $skippedNoProgram[$programCode] = ($skippedNoProgram[$programCode] ?? 0) + 1;
                continue;
            }

            // "BSIT 1A" -> year_level 1, letter "A". "BSE MATH 1" -> year_level 1, letter "".
            $suffix = trim(substr($row['section'], strlen($programCode)));
            preg_match('/^(\d+)([A-Za-z]*)$/', $suffix, $m);
            $yearLevel = (int) ($m[1] ?? 1);
            $letter = $m[2] ?? '';

            $sectionKey = $programCode . '|' . $yearLevel . '|' . $letter;

            if (! isset($sectionCache[$sectionKey])) {

                $section = Section::firstOrCreate([
                    'program_id' => $programCache[$programCode],
                    'year_level' => $yearLevel,
                    'section_letter' => $letter,
                    'school_year' => '2026-2027',
                    'semester' => '1st Semester',
                ]);

                $sectionCache[$sectionKey] = $section->id;

            }

            $subjectCode = $row['subject'];

            if (! isset($subjectCache[$subjectCode])) {

                $subject = Subject::firstOrCreate(['code' => $subjectCode]);

                $subjectCache[$subjectCode] = $subject->id;

            }

            $instructor = $row['instructor'];
            $facultyName = ($instructor && strtoupper($instructor) !== 'TBA') ? $instructor : null;

            ClassSchedule::create([
                'section_id' => $sectionCache[$sectionKey],
                'subject_id' => $subjectCache[$subjectCode],
                'personnel_id' => null,
                'faculty_name' => $facultyName,
                'room' => $row['room'],
                'day_of_week' => $row['day'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
            ]);

            $created++;

        }

        $this->command?->info("Imported {$created} class schedule entries.");
        $this->command?->info('Sections created: ' . count($sectionCache));
        $this->command?->info('Subjects created: ' . Subject::count());

        if ($skippedNoProgram) {
            $this->command?->warn('Skipped rows for unmatched program codes: ' . json_encode($skippedNoProgram));
        }
    }
}

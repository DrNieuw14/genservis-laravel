<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * The 10 real degree programs from the FY2026-2027 First Semester class
     * schedule (Other docs/Schedule-First-Semester-2026-2027.xls) — reference
     * data for Class Scheduling, not itemized schedule entries (those are
     * added by the Registrar through the new UI). Department links are a
     * best-guess mapping from program to the existing `departments` table
     * where confident (DIIT/DHM/DM) — BSIndT's department is genuinely
     * ambiguous (Industrial Technology sits under Teacher Education at some
     * SUCs, DIIT at others) and left as a guess Joseph can correct via
     * the Programs edit page.
     */
    public function run(): void
    {
        $deptByCode = Department::pluck('id', 'department_code');

        $programs = [
            ['code' => 'BSIT', 'title' => 'Bachelor of Science in Information Technology', 'dept' => 'DIIT'],
            ['code' => 'BSCS', 'title' => 'Bachelor of Science in Computer Science', 'dept' => 'DIIT'],
            ['code' => 'BSCpE', 'title' => 'Bachelor of Science in Computer Engineering', 'dept' => 'DIIT'],
            ['code' => 'BSHM', 'title' => 'Bachelor of Science in Hotel Management', 'dept' => null],
            ['code' => 'BSBA MM', 'title' => 'Bachelor of Science in Business Administration Major in Marketing Management', 'dept' => 'DM'],
            ['code' => 'BSBA HRM', 'title' => 'Bachelor of Science in Business Administration Major in Human Resource Management', 'dept' => 'DM'],
            ['code' => 'BSE ENG', 'title' => 'Bachelor of Secondary Education Major in English', 'dept' => null],
            ['code' => 'BSE MATH', 'title' => 'Bachelor of Secondary Education Major in Mathematics', 'dept' => null],
            ['code' => 'BSE SCI', 'title' => 'Bachelor of Secondary Education Major in Science', 'dept' => null],
            ['code' => 'BSIndT', 'title' => 'Bachelor of Science in Industrial Technology', 'dept' => null],
        ];

        // BSHM -> Department of Hotel Management, BSE * -> Department of
        // Teacher Education — matched by name since their department_code
        // columns aren't populated the same way DIIT/DM are.
        $dhm = Department::where('department_name', 'like', '%Hotel Management%')->value('id');
        $dte = Department::where('department_name', 'like', '%Teacher Education%')->value('id');

        foreach ($programs as $p) {

            $departmentId = match ($p['code']) {
                'BSHM' => $dhm,
                'BSE ENG', 'BSE MATH', 'BSE SCI', 'BSIndT' => $dte,
                default => $deptByCode[$p['dept']] ?? null,
            };

            Program::firstOrCreate(
                ['code' => $p['code']],
                [
                    'title' => $p['title'],
                    'department_id' => $departmentId,
                    'is_active' => true,
                ]
            );

        }
    }
}

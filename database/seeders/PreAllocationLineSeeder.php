<?php

namespace Database\Seeders;

use App\Models\PreAllocationLine;
use App\Models\ProgramReceiptExpenditure;
use Illuminate\Database\Seeder;

class PreAllocationLineSeeder extends Seeder
{
    private const PS = 'Personal Services';
    private const MOOE = 'Maintenance and Other Operating Expenses';
    private const CO = 'Capital Outlay';

    /**
     * Full UACS-level "Allotment Class/Object of Expenditure" detail behind the
     * PPA-level totals already seeded in `pre_allocations` — transcribed from
     * FY2026 PRE CARMONA, pages 3-6 (Fund164) and 7-11 (Fund101), re-rendered
     * right-side-up (the raw PDF pages are printed rotated 180°) to avoid
     * transcription errors. Only non-zero cells are stored — an absent
     * (ppa, uacs_code) combination means zero for that line, same convention
     * as `pre_allocations`.
     *
     * Each row: [fund_source, ppa, allotment_class, uacs_code, description, amount]
     */
    private function lines(): array
    {
        return [
            // --- PERSONAL SERVICES (Fund164, all GASS except Overload/Workload = MFO1) ---
            ['164', 'GASS', self::PS, '5010102000', 'Salaries Contractual Employees', 1088064.00],
            ['164', 'GASS', self::PS, '5010201001', 'Personnel Economic Allowance', 96000.00],
            ['164', 'GASS', self::PS, '5010204001', 'Clothing Allowance', 28000.00],
            ['164', 'GASS', self::PS, '5010205003', 'Subsistence Allowance', 13200.00],
            ['164', 'GASS', self::PS, '5010206004', 'Laundry Allowance', 1799.95],
            ['164', 'MFO1', self::PS, '5010210001', 'Overload/Workload', 2300000.00],
            ['164', 'GASS', self::PS, '5010211005', 'Hazard Pay', 126534.00],
            ['164', 'GASS', self::PS, '5010216001', 'MidYear Bonus', 90672.00],
            ['164', 'GASS', self::PS, '5010214001', 'Year Bonus', 90672.00],
            ['164', 'GASS', self::PS, '5010215001', 'Cash Gift', 20000.00],
            ['164', 'GASS', self::PS, '5010301000', 'Life and Retirement Insurance Contribution', 130567.68],
            ['164', 'GASS', self::PS, '5010302001', 'Pag-Ibig Contributions', 9600.00],
            ['164', 'GASS', self::PS, '5010303001', 'Philhealth Contributions', 27201.60],
            ['164', 'GASS', self::PS, '5010304001', 'ECIP Contributions', 4800.00],
            ['164', 'GASS', self::PS, '5010403000', 'Terminal Leave Benefits', 700000.00],

            // --- MAINTENANCE AND OTHER OPERATING EXPENSES (Fund164) ---
            ['164', 'STO', self::MOOE, '5020101000', 'Travelling Expense-Local', 150000.00],
            ['164', 'MFO1', self::MOOE, '5020101000', 'Travelling Expense-Local', 150000.00],
            ['164', 'MFO4', self::MOOE, '5020101000', 'Travelling Expense-Local', 100000.00],
            ['164', 'STO', self::MOOE, '5020102000', 'Travelling Expense-Foreign', 200000.00],
            ['164', 'MFO1', self::MOOE, '5020102000', 'Travelling Expense-Foreign', 250000.00],
            ['164', 'STO', self::MOOE, '5020201000', 'Training Expenses', 463818.34],
            ['164', 'MFO1', self::MOOE, '5020201000', 'Training Expenses', 100000.00],
            ['164', 'MFO3', self::MOOE, '5020201000', 'Training Expenses', 181181.66],
            ['164', 'MFO4', self::MOOE, '5020201000', 'Training Expenses', 600000.00],
            ['164', 'STO', self::MOOE, '5020301000', 'Office Supplies Expense', 474507.71],
            ['164', 'MFO1', self::MOOE, '5020301000', 'Office Supplies Expense', 140000.00],
            ['164', 'MFO3', self::MOOE, '5020301000', 'Office Supplies Expense', 22735.44],
            ['164', 'MFO4', self::MOOE, '5020301000', 'Office Supplies Expense', 65376.00],
            ['164', 'STO', self::MOOE, '5020302000', 'Accountable Forms Expenses', 20000.00],
            ['164', 'STO', self::MOOE, '5020303000', 'Non Accountable Forms Expenses', 329000.00],
            ['164', 'MFO1', self::MOOE, '5020303000', 'Non Accountable Forms Expenses', 200000.00],
            ['164', 'STO', self::MOOE, '5020307000', 'Drugs & Medicine Expense', 50000.00],
            ['164', 'STO', self::MOOE, '5020308000', 'Medical, Dental and Laboratory Supplies Expense', 10000.00],
            ['164', 'STO', self::MOOE, '5020309000', 'Fuel, Oil and Lubricants Expense', 140000.00],
            ['164', 'MFO1', self::MOOE, '5020309000', 'Fuel, Oil and Lubricants Expense', 80000.00],
            ['164', 'MFO1', self::MOOE, '5020311000', 'Textbooks and Instructional Materials Expenses', 550000.00],
            ['164', 'STO', self::MOOE, '5020399000', 'Other Supplies & Materials Expenses', 600000.00],
            ['164', 'MFO1', self::MOOE, '5020399000', 'Other Supplies & Materials Expenses', 470100.00],
            ['164', 'MFO3', self::MOOE, '5020399000', 'Other Supplies & Materials Expenses', 51500.00],
            ['164', 'STO', self::MOOE, '5020321002', 'Semi Expendable Office Equipment', 286677.40],
            ['164', 'MFO1', self::MOOE, '5020321003', 'Semi Expendable ICT Equipment', 359900.00],
            ['164', 'MFO4', self::MOOE, '5020321003', 'Semi Expendable ICT Equipment', 88100.00],
            ['164', 'MFO1', self::MOOE, '5020321012', 'Semi Expendable Sports Equipment', 300000.00],
            ['164', 'STO', self::MOOE, '5020321099', 'Semi Expendable Other Machinery and Equipment', 299551.30],
            ['164', 'STO', self::MOOE, '5020322001', 'Semi Expendable Furniture and Fixtures', 262250.00],
            ['164', 'MFO1', self::MOOE, '5020322001', 'Semi Expendable Furniture and Fixtures', 400000.00],
            ['164', 'MFO4', self::MOOE, '5020322001', 'Semi Expendable Furniture and Fixtures', 30000.00],
            ['164', 'STO', self::MOOE, '5020401000', 'Water Expenses', 400000.00],
            ['164', 'STO', self::MOOE, '5020402000', 'Electricity Expenses', 4500000.00],
            ['164', 'STO', self::MOOE, '5020501000', 'Postage & Deliveries', 12500.00],
            ['164', 'STO', self::MOOE, '5020502001', 'Telephone Expenses-Mobile', 3600.00],
            ['164', 'STO', self::MOOE, '5020503000', 'Internet Expenses', 500000.00],
            ['164', 'MFO3', self::MOOE, '5020601002', 'Rewards and Incentives', 849674.00],
            ['164', 'MFO4', self::MOOE, '5020702000', 'Research, Exploration and Development', 200000.00],
            ['164', 'STO', self::MOOE, '5021101000', 'Legal Services', 20000.00],
            ['164', 'MFO3', self::MOOE, '5021101000', 'Legal Services', 30000.00],
            ['164', 'MFO4', self::MOOE, '5021101000', 'Legal Services', 10000.00],
            ['164', 'MFO1', self::MOOE, '5021199000', 'Other Professional Services', 36106054.40],
            ['164', 'MFO3', self::MOOE, '5021199000', 'Other Professional Services', 90000.00],
            ['164', 'MFO4', self::MOOE, '5021199000', 'Other Professional Services', 100000.00],
            ['164', 'STO', self::MOOE, '5021202000', 'Janitorial Services', 1725836.80],
            ['164', 'STO', self::MOOE, '5021203000', 'Security Services', 3319821.00],
            ['164', 'STO', self::MOOE, '5021299000', 'Other General Services', 1898636.80],
            ['164', 'MFO3', self::MOOE, '5021299000', 'Other General Services', 242398.40],
            ['164', 'MFO4', self::MOOE, '5021299000', 'Other General Services', 242398.40],
            ['164', 'STO', self::MOOE, '5021303003', 'Repair & Maintenance - Sewer Systems', 25000.00],
            ['164', 'STO', self::MOOE, '5021304001', 'Repair & Maintenance - Buildings', 100000.00],
            ['164', 'STO', self::MOOE, '5021304002', 'Repair & Maintenance - School Buildings', 100000.00],
            ['164', 'STO', self::MOOE, '5021304006', 'Repair & Maintenance - Hostels and Dormitories', 50000.00],
            ['164', 'STO', self::MOOE, '5021304099', 'Repair & Maintenance - Other Structures', 75000.00],
            ['164', 'STO', self::MOOE, '5021305002', 'Repair & Maintenance - Office Equipment', 50000.00],
            ['164', 'STO', self::MOOE, '5021305003', 'Repair & Maintenance - ICT Equipment', 50000.00],
            ['164', 'STO', self::MOOE, '5021306001', 'Repair & Maintenance - Motor Vehicles', 200000.00],
            ['164', 'MFO1', self::MOOE, '5021306001', 'Repair & Maintenance - Motor Vehicles', 50000.00],
            ['164', 'STO', self::MOOE, '5021501000', 'Taxes, Duties & Licenses', 8000.00],
            ['164', 'STO', self::MOOE, '5021502000', 'Fidelity Bond Premiums', 5000.00],
            ['164', 'STO', self::MOOE, '5021503000', 'Insurance Expenses', 650000.00],
            ['164', 'STO', self::MOOE, '5021601000', 'Labor & Wages', 120000.00],
            ['164', 'STO', self::MOOE, '5029902000', 'Printing and Binding Expenses', 280000.00],
            ['164', 'MFO1', self::MOOE, '5029902000', 'Printing and Binding Expenses', 200000.00],
            ['164', 'STO', self::MOOE, '5029903000', 'Representation Expense', 1000000.00],
            ['164', 'MFO1', self::MOOE, '5029903000', 'Representation Expense', 1044454.22],
            ['164', 'MFO3', self::MOOE, '5029903000', 'Representation Expense', 170500.00],
            ['164', 'MFO4', self::MOOE, '5029903000', 'Representation Expense', 436744.52],
            ['164', 'STO', self::MOOE, '5029905002', 'Rent Expense - Land', 35000.00],
            ['164', 'MFO3', self::MOOE, '5029905002', 'Rent Expense - Land', 3600.00],
            ['164', 'STO', self::MOOE, '5029905003', 'Rent Expense - Motor Vehicle', 145000.00],
            ['164', 'MFO3', self::MOOE, '5029905003', 'Rent Expense - Motor Vehicle', 5000.00],
            ['164', 'STO', self::MOOE, '5029905004', 'Rent Expense - Equipment', 475000.00],
            ['164', 'MFO1', self::MOOE, '5029906000', 'Membership Dues & Contributions to Organizations', 50000.00],
            ['164', 'STO', self::MOOE, '5029999099', 'Other Maintenance and Operating Expenses', 105000.00],

            // --- CAPITAL OUTLAY (Fund164) ---
            ['164', 'STO', self::CO, '1060503000', 'ICT Equipment & Software', 65000.00],
            ['164', 'MFO1', self::CO, '1060599000', 'Other Machinery and Equipment', 250000.00],
            ['164', 'STO', self::CO, '1060701000', 'Furniture & Fixtures', 150000.00],
            ['164', 'MFO1', self::CO, '1060701000', 'Furniture & Fixtures', 150000.00],
            ['164', 'MFO3', self::CO, '1060701000', 'Furniture & Fixtures', 226029.42],

            // --- Fund101 (Trust/Misc) — only one non-zero line ---
            ['101', 'GASS', self::MOOE, '5021299000', 'Other General Services', 90000.00],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pre = ProgramReceiptExpenditure::where('year', 2026)->first();

        if (! $pre) {
            $this->command?->error('Run ProgramReceiptExpenditureSeeder first — no FY2026 PRE found.');
            return;
        }

        if ($pre->allocationLines()->exists()) {
            $this->command?->info('PRE allocation lines already seeded — skipping.');
            return;
        }

        $checkTotals = [];

        foreach ($this->lines() as [$fund, $ppa, $class, $uacs, $description, $amount]) {

            PreAllocationLine::create([
                'pre_id' => $pre->id,
                'fund_source' => $fund,
                'ppa' => $ppa,
                'allotment_class' => $class,
                'uacs_code' => $uacs,
                'description' => $description,
                'amount' => $amount,
            ]);

            $key = "{$fund}-{$ppa}-{$class}";
            $checkTotals[$key] = ($checkTotals[$key] ?? 0) + $amount;

        }

        // Cross-check every subtotal against the already-verified pre_allocations
        // PPA-level totals seeded earlier — if these don't match, something in
        // this line-item transcription is wrong.
        $expected = [
            '164-GASS-' . self::PS => 2427111.23,
            '164-MFO1-' . self::PS => 2300000.00,
            '164-STO-' . self::MOOE => 19139199.35,
            '164-MFO1-' . self::MOOE => 40450508.62,
            '164-MFO3-' . self::MOOE => 1646589.50,
            '164-MFO4-' . self::MOOE => 1872618.92,
            '164-STO-' . self::CO => 215000.00,
            '164-MFO1-' . self::CO => 400000.00,
            '164-MFO3-' . self::CO => 226029.42,
            '101-GASS-' . self::MOOE => 90000.00,
        ];

        // Known real discrepancy in the source document itself (not a transcription
        // error — verified by re-checking the raw scan multiple times): page 2's
        // PPA summary states GASS has a 90,000 MOOE line, but the itemized
        // UACS-level breakdown (pages 3-6) has zero GASS entries anywhere in its
        // MOOE section — every one of those rows is STO/MFO1/MFO3/MFO4. The
        // detail pages' own printed subtotal (63,108,916.39) is short of the
        // summary page's MOOE total (63,198,916.39) by exactly that 90,000.
        // pre_allocations (PPA-level, already seeded) keeps the summary page's
        // figure since that's the authoritative ceiling; this line-item table
        // faithfully reflects what's actually itemized, gap included.
        $this->command?->warn('Note: source document has an unreconciled 90,000 GASS/MOOE gap between its summary page and itemized detail — see PreAllocationLineSeeder comments.');

        $allOk = true;

        foreach ($expected as $key => $expectedAmount) {

            $actual = round($checkTotals[$key] ?? 0, 2);

            $ok = abs($actual - $expectedAmount) < 0.01;

            $allOk = $allOk && $ok;

            $this->command?->info(
                ($ok ? '[OK] ' : '[MISMATCH] ') . $key . ': ' . number_format($actual, 2) . ' (expected ' . number_format($expectedAmount, 2) . ')'
            );

        }

        $this->command?->info($allOk ? 'All subtotals verified.' : 'SOME SUBTOTALS DO NOT MATCH — review transcription.');
    }
}

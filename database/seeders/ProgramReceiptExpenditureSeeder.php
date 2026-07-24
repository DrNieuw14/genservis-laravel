<?php

namespace Database\Seeders;

use App\Models\PreAllocation;
use App\Models\ProgramReceiptExpenditure;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProgramReceiptExpenditureSeeder extends Seeder
{
    /**
     * Real FY2026 Program of Receipts and Expenditures, Carmona Campus —
     * transcribed from the BOR-approved source document (see Other docs/FY2026
     * PRE CARMONA (1).pdf) and cross-verified: the Fund 164 MOOE/Capital-Outlay/
     * Total columns below sum exactly to the document's own grand-total row
     * (63,198,916.39 / 841,029.42 / 68,767,057.04), and the income figure
     * cross-checks between the document's income-sources page and its PPA
     * summary page (78,665,185.63 both places).
     */
    public function run(): void
    {
        $rochelle = User::where('username', 'rochelle')->first();

        if (! $rochelle) {
            return;
        }

        $pre = ProgramReceiptExpenditure::firstOrCreate(
            ['year' => 2026],
            [
                'total_projected_income' => 78665185.63,
                'status' => 'Approved',
                'prepared_by' => $rochelle->id,
                'remarks' => 'BOR-approved FY2026 PRE, Carmona Campus.',
            ]
        );

        $rows = [
            // Fund 164 — Regular Income
            ['fund_source' => '164', 'ppa' => 'GASS', 'personal_services' => 2427111.23, 'mooe' => 90000.00, 'capital_outlay' => 0, 'infrastructure' => 0],
            ['fund_source' => '164', 'ppa' => 'STO', 'personal_services' => 0, 'mooe' => 19139199.35, 'capital_outlay' => 215000.00, 'infrastructure' => 0],
            ['fund_source' => '164', 'ppa' => 'MFO1', 'personal_services' => 2300000.00, 'mooe' => 40450508.62, 'capital_outlay' => 400000.00, 'infrastructure' => 0],
            ['fund_source' => '164', 'ppa' => 'MFO2', 'personal_services' => 0, 'mooe' => 0, 'capital_outlay' => 0, 'infrastructure' => 0],
            ['fund_source' => '164', 'ppa' => 'MFO3', 'personal_services' => 0, 'mooe' => 1646589.50, 'capital_outlay' => 226029.42, 'infrastructure' => 0],
            ['fund_source' => '164', 'ppa' => 'MFO4', 'personal_services' => 0, 'mooe' => 1872618.92, 'capital_outlay' => 0, 'infrastructure' => 0],

            // Fund 101 — Trust / Misc (only GASS has a non-zero line: "Other General Services")
            ['fund_source' => '101', 'ppa' => 'GASS', 'personal_services' => 0, 'mooe' => 90000.00, 'capital_outlay' => 0, 'infrastructure' => 0],
        ];

        foreach ($rows as $row) {

            PreAllocation::firstOrCreate(
                [
                    'pre_id' => $pre->id,
                    'fund_source' => $row['fund_source'],
                    'ppa' => $row['ppa'],
                ],
                $row
            );

        }
    }
}

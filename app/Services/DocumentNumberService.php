<?php

namespace App\Services;

use App\Models\ProcurementPlan;

class DocumentNumberService
{
    /**
     * Generate PPMP Number
     *
     * Format:
     * PPMP-2027-0001
     */
    public static function generatePPMPNumber($year)
    {
        $lastPlan = ProcurementPlan::where('year', $year)
            ->latest('id')
            ->first();

        $nextNumber = 1;

        if ($lastPlan) {

            $parts = explode('-', $lastPlan->plan_number);

            if (count($parts) === 3) {

                $nextNumber = ((int) $parts[2]) + 1;

            }

        }

        return sprintf(
            'PPMP-%s-%04d',
            $year,
            $nextNumber
        );
    }
}
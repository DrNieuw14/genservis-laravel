<?php

namespace App\Services;

use App\Models\ProcurementPlan;
use App\Models\PurchaseRequest;

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

    /**
     * Generate Purchase Request Number
     *
     * Format:
     * PR-2027-0001
     */
    public static function generatePRNumber($year)
    {
        $lastPr = PurchaseRequest::whereYear('pr_date', $year)
            ->latest('id')
            ->first();

        $nextNumber = 1;

        if ($lastPr) {

            $parts = explode('-', $lastPr->pr_number);

            if (count($parts) === 3) {

                $nextNumber = ((int) $parts[2]) + 1;

            }

        }

        return sprintf(
            'PR-%s-%04d',
            $year,
            $nextNumber
        );
    }
}
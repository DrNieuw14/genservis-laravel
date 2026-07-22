<?php

namespace Database\Seeders;

use App\Models\ClinicMedicine;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * One-time real-data import from the CvSU Campus Health Services Unit's
 * actual 2026 medicine/medical supply stock sheet (INVENTORY_CLINIC.docx,
 * prepared by Kris C. Alforte, RN — Campus Nurse). `current_stock` is
 * seeded from the sheet's own "REMAIN" column — the one figure meant to
 * reflect what's actually on hand right now, which is what dispensing
 * decrements. "LESS" (the sheet's own third quantity column) is dropped
 * entirely: on the source sheet it's sometimes a count and sometimes a
 * percentage of the same row (e.g. Efficascent Oil: REMAIN "70%" / LESS
 * "30%" for a single bottle), so its meaning isn't consistent enough to
 * carry into a live system without guessing. A few rows needed a
 * documented fallback because REMAIN itself was blank or a percentage
 * rather than a count — see the 'notes' field on those rows.
 */
class ClinicMedicineSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // ── Sheet 1 ──
            ['Paracetamol 500mg', 'Fluguard', 'Tablet', 100, 32, 'APRIL 2030'],
            ['Hyoscine 10mg', 'AMB', 'Tablet', 50, 47, 'AUGUST 2028'],
            ['Cetirizine 10mg', 'CTZ', 'Tablet', 100, 94, 'DECEMBER 2028'],
            ['Loperamide 2mg', 'Scheele', 'Capsule', 50, 50, 'MARCH 2030'],
            ['Mefenamic Acid 250mg', 'Mefemed', 'Capsule', 50, 20, 'DECEMBER 2028'],
            ['Mefenamic Acid 500mg', 'Mefein', 'Caplet', 10, 10, 'JUNE 2028'],
            ['Cinnarizine 25mg', 'Rizine', 'Tablet', 20, 20, 'JUNE 2028'],
            ['Isopropyl Alcohol', 'Alco-plus', 'Bottle', 1, 1, 'JANUARY 2029'],
            ['Efficascent Oil Extreme 100ml', null, 'Bottle', 1, null, 'MARCH 2029',
                "Sheet listed REMAIN as \"70%\" (a percentage, not a count) for this single bottle — stock seeded as 1 (unopened) pending a real count."],
            ['Micropore 1 inch', null, 'Roll', 1, 1, null],
            ['Micropore 1/2 inch', null, 'Roll', 1, 1, null],

            // ── Sheet 2 ──
            ['Facemask Surgical 3ply 50s', 'Indoplas', 'Box', 5, 0, null],
            ['Hand Sanitizer (Pump) 500ml', 'Cleace', 'Bottle', 10, 6, null],
            ['Paracetamol 500mg', 'Biogesic', 'Tablet', 500, 0, 'AUGUST 2026'],
            ['Phenylephrine HCL 10mg / Chlorphenamine Maleate 2mg / Paracetamol 500mg', 'Neozep', 'Tablet', 500, 131, 'AUGUST 2026'],
            ['Mefenamic Acid 500mg', null, 'Capsule', 300, 0, 'SEPTEMBER 2026'],
            ['Loratadine 10mg', null, 'Capsule', 36, 8, 'MARCH 2027'],
            ['Aluminum Hydroxide 178mg + Magnesium Hydroxide 233mg + Simethicone 30mg', 'Kremil-S', 'Tablet', 200, 58, 'JANUARY 2027'],
            ['Loperamide 2mg', 'Imodium', 'Capsule', 400, 292, 'JUNE 2029'],
            ['Ibuprofen 200mg + Paracetamol 325mg', 'Alaxan', 'Capsule', 300, 236, 'OCTOBER 2027'],
            ['Meclizine 25mg', 'Bonamine', 'Tablet', 60, 31, 'NOVEMBER 2026'],
            ['Salonpas 6.5cm x 4.2cm', 'Hisamitsu', 'Patch', 200, 0, 'OCTOBER 2026'],
            ['White Flower Oil', 'Embrocation', 'Bottle (Size No. 3)', 10, 3, 'DECEMBER 2028'],
            ['Pain Killer Liniment 120ml', 'Omega', 'Bottle', 3, 3, 'DECEMBER 2026'],
            ['Wound Solution 120ml', 'Betadine', 'Bottle', 3, 2, 'JANUARY 2029'],
            ['Adhesive Bandage, Flexible Fabric', 'Bandaid', 'Patch', 300, 177, 'JANUARY 2028'],
            ['Elastic Bandage 4in x 5yards x 1roll', 'Mediplast', 'Roll', 100, 55, 'MAY 2029'],
            ['Aceite de Manzanilla 120ml', null, 'Bottle', 3, 1, null],
            ['Methylsalicylate 21g + Camphor 7.5mg + Menthol 6.0g Oil 50ml', 'Efficascent Oil', 'Bottle', 3, 3, 'MARCH 2029'],
            ['Cotton Balls (50 balls)', 'Cleene', 'Pack', 20, 1, 'JULY 2026'],
            ['Ethyl Alcohol 70% solution 500ml', 'Cleene', 'Box', 2, null, null,
                'Sheet left REMAIN blank for this row — stock seeded from the original QUANTITY (2) pending a real count.'],
            ['Ibuprofen 200mg', 'Advil', 'Softgel', 60, 25, 'AUGUST 2026'],

            // ── Sheet 3 (all listed at 0 remaining on the source sheet) ──
            ['Antacid', 'Shelogel (Scheele)', 'Box', 100, 0, null],
            ['Hyoscine 10mg', 'Hyosaph (Sapphire)', 'Tablet', 100, 0, null],
            ['Loperamide 2mg', 'Scheele', 'Capsule', 100, 0, null],
            ['Loratadine 10mg', 'Histadine (Health Saver)', 'Tablet', 100, 0, null],
            ['Mefenamic Acid 500mg', 'Analmin (Flamingo)', 'Capsule', 100, 0, null],
            ['Chlorphenamine Maleate + Phenylephrine + Paracetamol', 'Symdex (Allied)', 'Tablet', 100, 0, null],
        ];

        foreach ($rows as $row) {
            [$name, $brand, $unit, $quantityReceived, $currentStock, $expiration, $notes] = array_pad($row, 7, null);

            ClinicMedicine::create([
                'name' => $name,
                'brand' => $brand,
                'unit' => $unit,
                'quantity_received' => $quantityReceived,
                'current_stock' => $currentStock ?? $quantityReceived,
                'expiration_date' => $expiration ? Carbon::parse($expiration)->startOfMonth() : null,
                'notes' => $notes,
            ]);
        }
    }
}

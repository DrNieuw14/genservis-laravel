<?php

namespace Database\Seeders;

use App\Models\ProcurementClassification;
use Illuminate\Database\Seeder;

class ProcurementClassificationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/procurement_classifications.json');

        $rows = json_decode(file_get_contents($path), true);

        foreach ($rows as $row) {

            ProcurementClassification::firstOrCreate([
                'part' => $row['part'],
                'main_category' => $row['main_category'],
                'sub_category_a' => $row['sub_category_a'],
                'sub_category_b' => $row['sub_category_b'],
                'sub_category_c' => $row['sub_category_c'],
                'code' => $row['code'],
                'uacs_code' => $row['uacs_code'],
            ], [
                'is_active' => true,
            ]);

        }
    }
}

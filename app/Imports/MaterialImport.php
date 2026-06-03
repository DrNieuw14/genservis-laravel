<?php

namespace App\Imports;

use App\Models\Material;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Department;
use App\Models\MaterialLog;
use App\Models\InventoryMovement;

use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MaterialImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        
        if (empty($row['brand_model_designation'])) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | CATEGORY FROM EXCEL
        |--------------------------------------------------------------------------
        */

        $category = Category::firstOrCreate([
            'name' => trim($row['category'])
        ]);

        /*
        |--------------------------------------------------------------------------
        | UNIT
        |--------------------------------------------------------------------------
        */

        $unit = Unit::firstOrCreate([
            'name' => strtoupper(trim((string) $row['unit']))
        ]);

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT FROM EXCEL
        |--------------------------------------------------------------------------
        */

        $department = Department::firstOrCreate([
            'department_name' => trim($row['department'])
        ], [
            'department_code' => 'STK',
            'description' => 'Central inventory stockroom'
        ]);

        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATES
        |--------------------------------------------------------------------------
        */

        $existing = Material::where(
            'name',
            trim($row['brand_model_designation'])
        )->first();

        if ($existing) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE MATERIAL
        |--------------------------------------------------------------------------
        */

        $material = Material::create([

            'name' => trim($row['brand_model_designation']),

            'department_id' => $department->id,

            'category_id' => $category->id,

            'unit_id' => $unit->id,

            'quantity' => (int) $row['quantity'],

            'threshold' => 5,

            'created_by' => auth()->id() ?? 1,

        ]);

        /*
        |--------------------------------------------------------------------------
        | INVENTORY MOVEMENT
        |--------------------------------------------------------------------------
        */

        InventoryMovement::create([

            'material_id' => $material->id,

            'movement_type' => 'restock',

            'quantity' => (int) $row['quantity'],

            'previous_stock' => 0,

            'new_stock' => (int) $row['quantity'],

            'remarks' => 'Imported from Excel',

            'performed_by' => auth()->id() ?? 1,

        ]);

        /*
        |--------------------------------------------------------------------------
        | MATERIAL LOG
        |--------------------------------------------------------------------------
        */

        MaterialLog::create([

            'material_id' => $material->id,

            'user_id' => auth()->id() ?? 1,

            'action' => 'stock_in',

            'quantity' => (int) $row['quantity'],

            'remarks' => 'Imported from Excel',

        ]);

        return $material;
    }
}
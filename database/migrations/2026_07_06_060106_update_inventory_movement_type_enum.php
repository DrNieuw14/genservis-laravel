<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE inventory_movements
            MODIFY movement_type ENUM(
                'initial_stock',
                'restock',
                'request',
                'damage',
                'transfer',
                'adjustment',
                'return'
            ) NOT NULL;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE inventory_movements
            MODIFY movement_type ENUM(
                'restock',
                'request',
                'damage',
                'transfer'
            ) NOT NULL;
        ");
    }
};
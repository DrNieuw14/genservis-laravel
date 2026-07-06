<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE inventory_movements
            MODIFY movement_type VARCHAR(50) NOT NULL;
        ");
    }

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
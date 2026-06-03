<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('material_restock_logs', function (Blueprint $table) {
            $table->string('batch_no')->nullable()->after('material_id');

            $table->integer('quantity_remaining')
                ->default(0)
                ->after('added_stock');

            $table->string('invoice_no')
                ->nullable()
                ->after('supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_restock_logs', function (Blueprint $table) {
            //
        });
    }
};

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
        Schema::table('procurement_plan_items', function (Blueprint $table) {

            // Which PRE Program/Project Activity (PPA/MFO) this item's budget draws from.
            // Nullable — existing items and departments not yet reconciled against a PRE stay untagged.
            $table->enum('ppa', [
                'GASS',
                'STO',
                'MFO1',
                'MFO2',
                'MFO3',
                'MFO4',
            ])->nullable()->after('source_of_fund');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procurement_plan_items', function (Blueprint $table) {
            $table->dropColumn('ppa');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_items', function (Blueprint $table) {
            // Needed on the ICS/PAR issuance slip's item table — captured
            // here on the inventory item itself so every issuance drawn
            // from it can snapshot a real value instead of leaving it blank.
            $table->string('unit')->nullable()->after('property_name');
            $table->string('estimated_useful_life')->nullable()->after('date_acquired');
        });
    }

    public function down(): void
    {
        Schema::table('property_items', function (Blueprint $table) {
            $table->dropColumn(['unit', 'estimated_useful_life']);
        });
    }
};

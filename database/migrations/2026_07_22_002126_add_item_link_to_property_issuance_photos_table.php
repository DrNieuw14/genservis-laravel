<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_issuance_photos', function (Blueprint $table) {
            // Nullable — a photo can also be general (e.g. an overview
            // shot) rather than tied to one specific line item.
            $table->foreignId('property_issuance_item_id')
                ->nullable()
                ->after('property_issuance_id')
                ->constrained('property_issuance_items')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('property_issuance_photos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('property_issuance_item_id');
        });
    }
};

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
        Schema::create('building_inspection_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('building_inspection_id')
                ->constrained()
                ->cascadeOnDelete();

            // One of the 6 fixed PPLS-QF-03 categories — see
            // BuildingInspection::CATEGORIES. Not user-defined, so a plain
            // string key rather than a lookup table.
            $table->string('category');

            // Indexes (as strings) into that category's fixed observation
            // list — which standard questions were checked on this
            // inspection. Kept as JSON since the list itself lives in code,
            // not the database.
            $table->json('flagged_observations')->nullable();

            $table->text('other_observations')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->unique(['building_inspection_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_inspection_items');
    }
};

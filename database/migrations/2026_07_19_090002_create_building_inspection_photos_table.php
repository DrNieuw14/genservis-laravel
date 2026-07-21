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
        Schema::create('building_inspection_photos', function (Blueprint $table) {
            $table->id();

            // Scoped to the category item, not the inspection as a whole —
            // a photo of a cracked wall belongs under Building Interior,
            // not in one undifferentiated gallery.
            $table->foreignId('building_inspection_item_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('path');

            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_inspection_photos');
    }
};

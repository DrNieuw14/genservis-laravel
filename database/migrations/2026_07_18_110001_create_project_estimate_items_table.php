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
        Schema::create('project_estimate_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_estimate_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('description');
            $table->string('unit')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_cost', 12, 2);

            // Drives the Section 3 Materials/Equipment vs Labor breakdown —
            // matches the two categories in the real PPLS estimate form.
            $table->enum('category', ['materials_equipment', 'labor'])
                ->default('materials_equipment');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_estimate_items');
    }
};

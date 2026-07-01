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
        Schema::create('procurement_plan_items', function (Blueprint $table) {

            $table->id();

            // Parent PPMP
            $table->foreignId('plan_id')
                ->constrained('procurement_plans')
                ->cascadeOnDelete();

            // Existing Inventory Item (optional)
            $table->foreignId('material_id')
                ->nullable()
                ->constrained('materials')
                ->nullOnDelete();

            // Manual Item Name
            $table->string('material_name');

            // Description
            $table->text('description')->nullable();

            // Unit
            $table->foreignId('unit_id')
                ->nullable()
                ->constrained('units')
                ->nullOnDelete();

            // Estimated Cost
            $table->decimal('estimated_unit_cost', 15, 2)->default(0);

            // Quarterly Quantities
            $table->integer('q1')->default(0);
            $table->integer('q2')->default(0);
            $table->integer('q3')->default(0);
            $table->integer('q4')->default(0);

            // Computed Annual Quantity
            $table->integer('annual_quantity')->default(0);

            // Computed Annual Cost
            $table->decimal('annual_cost', 15, 2)->default(0);

            // Procurement Priority
            $table->enum('priority', [
                'Low',
                'Medium',
                'High',
                'Critical'
            ])->default('Medium');

            // Procurement Details
            $table->string('procurement_method')->nullable();

            $table->string('source_of_fund')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_plan_items');
    }
};

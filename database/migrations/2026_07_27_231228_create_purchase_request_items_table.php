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
        Schema::create('purchase_request_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('purchase_request_id')
                ->constrained('purchase_requests')
                ->cascadeOnDelete();

            // Which planned PPMP line this PR item draws from — inherits that
            // item's ppa/material/UACS classification, no separate tagging needed.
            $table->foreignId('procurement_plan_item_id')
                ->constrained('procurement_plan_items')
                ->cascadeOnDelete();

            $table->integer('quantity_requested');

            // Snapshot at time of request — the planned item's cost may be edited later.
            $table->decimal('unit_cost', 15, 2);

            $table->decimal('total_cost', 15, 2);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_items');
    }
};

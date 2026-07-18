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
        Schema::create('procurement_plan_item_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('plan_id')
                ->constrained('procurement_plans')
                ->cascadeOnDelete();

            // Snapshot, since the item itself may no longer exist (deleted).
            $table->string('material_name');

            $table->enum('action', ['edited', 'deleted']);

            $table->text('reason')->nullable();

            $table->foreignId('performed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_plan_item_logs');
    }
};

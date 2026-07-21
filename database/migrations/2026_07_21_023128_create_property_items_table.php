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
        Schema::create('property_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->string('property_name');

            // Property tag / ICS number — real-world government PPE items
            // are individually tagged, but not every item necessarily has
            // one on hand yet, so nullable rather than required.
            $table->string('property_number')->nullable();

            $table->text('description')->nullable();

            // Bulk-identical items sharing one row (e.g. "10 chairs") are
            // allowed via quantity, rather than forcing one row per unit.
            $table->unsignedInteger('quantity')->default(1);

            $table->decimal('unit_value', 12, 2)->nullable();
            $table->date('date_acquired')->nullable();

            $table->enum('condition', ['Good', 'Damaged', 'For Repair', 'Unserviceable'])
                ->default('Good');

            $table->text('remarks')->nullable();

            $table->foreignId('created_by')
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
        Schema::dropIfExists('property_items');
    }
};

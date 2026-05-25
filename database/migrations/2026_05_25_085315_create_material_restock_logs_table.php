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
        Schema::create('material_restock_logs', function (Blueprint $table) {

            $table->id();

            // MATERIAL
            $table->foreignId('material_id')
                  ->constrained()
                  ->onDelete('cascade');

            // STOCK TRACKING
            $table->integer('previous_stock');

            $table->integer('added_stock');

            $table->integer('new_stock');

            // OPTIONAL DETAILS
            $table->string('supplier')->nullable();

            $table->text('remarks')->nullable();

            // USER WHO RESTOCKED
            $table->foreignId('restocked_by')
                  ->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_restock_logs');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('material_request_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('request_id')
                ->constrained('material_requests')
                ->onDelete('cascade');

            $table->foreignId('material_id')
                ->constrained('materials')
                ->onDelete('cascade');

            $table->integer('quantity');
            $table->text('purpose')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_request_items');
    }
};

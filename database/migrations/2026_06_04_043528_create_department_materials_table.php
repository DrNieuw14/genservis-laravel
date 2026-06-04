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
        Schema::create('department_materials', function (Blueprint $table) {

            $table->id();

            $table->foreignId('department_id');

            $table->foreignId('material_id');

            $table->integer('quantity');

            $table->foreignId('request_id');

            $table->foreignId('released_by');

            $table->timestamp('released_at');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_materials');
    }
};

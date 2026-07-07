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
        Schema::create('positions', function (Blueprint $table) {

            $table->id();

            $table->string('position_name')->unique();

            $table->string('position_code', 30)
                  ->nullable()
                  ->unique();

            $table->text('description')
                  ->nullable();

            $table->boolean('is_active')
                  ->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
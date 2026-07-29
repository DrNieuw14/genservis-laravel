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
        Schema::create('subjects', function (Blueprint $table) {

            $table->id();

            // e.g. "DCIT 22", "GNED 11", "MATH 3" — the code students/faculty
            // actually recognize, shown on every schedule grid.
            $table->string('code')->unique();

            $table->string('title')->nullable();

            $table->decimal('lecture_units', 4, 1)->default(0);

            $table->decimal('lab_units', 4, 1)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};

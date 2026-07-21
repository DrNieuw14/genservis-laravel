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
        Schema::create('building_inspections', function (Blueprint $table) {
            $table->id();

            $table->string('reference_no')->unique();

            $table->string('building_name');
            $table->string('building_in_charge')->nullable();
            $table->date('inspection_date');

            $table->foreignId('inspected_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // Free text, not a User FK — the PPS Director signing off may
            // not have (or need) a GenServis account, matching how the
            // paper form's "Noted by" line is just a written name.
            $table->string('noted_by')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_inspections');
    }
};

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
        Schema::create('health_consultation_medicines', function (Blueprint $table) {
            $table->id();

            $table->foreignId('health_consultation_id')->constrained()->cascadeOnDelete();

            // Nullable + snapshotted name/unit, same "legal record snapshot"
            // convention as PropertyIssuanceItem — deleting or editing the
            // ClinicMedicine later must never change what a past
            // consultation record says was actually dispensed.
            $table->foreignId('clinic_medicine_id')->nullable()->constrained()->nullOnDelete();
            $table->string('medicine_name');
            $table->string('unit')->nullable();

            $table->integer('quantity');
            $table->text('notes')->nullable();

            $table->foreignId('dispensed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_consultation_medicines');
    }
};

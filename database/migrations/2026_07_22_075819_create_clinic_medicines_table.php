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
        Schema::create('clinic_medicines', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('unit')->nullable();

            // "Remain" on the source inventory sheet — the live count,
            // decremented whenever medicine is dispensed to a patient.
            $table->integer('current_stock')->default(0);

            // "Quantity" on the source sheet — how much was originally
            // stocked, kept only as reference, never decremented.
            $table->integer('quantity_received')->nullable();

            $table->date('expiration_date')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_medicines');
    }
};

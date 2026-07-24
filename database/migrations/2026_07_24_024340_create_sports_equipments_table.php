<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sports_equipments', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('unit')->default('pcs');

            // Total owned quantity. Available-to-borrow is always computed
            // from this minus whatever's currently out (status = approved),
            // never stored, so it can never drift out of sync.
            $table->integer('total_quantity');

            $table->string('image')->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sports_equipments');
    }
};

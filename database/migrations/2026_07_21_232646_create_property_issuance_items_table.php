<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_issuance_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_issuance_id')
                ->constrained()
                ->cascadeOnDelete();

            // Traceability only, nullable — the fields below are a
            // snapshot taken at issuance time so an already-printed slip
            // never silently changes if the source item is later edited.
            $table->foreignId('property_item_id')
                ->nullable()
                ->constrained('property_items')
                ->nullOnDelete();

            $table->string('property_name');
            $table->string('property_number')->nullable();
            $table->string('unit')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->date('date_acquired')->nullable();
            $table->string('estimated_useful_life')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_issuance_items');
    }
};

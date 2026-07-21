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
        Schema::create('energy_conservation_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('energy_conservation_report_id');
            $table->foreign('energy_conservation_report_id', 'fk_eca_attachments_report')
                ->references('id')->on('energy_conservation_reports')
                ->cascadeOnDelete();

            // Matches the template's Attachments checklist: the electric
            // bill copy is the required one, photo/other are optional.
            $table->enum('type', ['electric_bill', 'photo', 'other'])->default('other');

            $table->string('path');

            $table->foreignId('uploaded_by')
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
        Schema::dropIfExists('energy_conservation_attachments');
    }
};

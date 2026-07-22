<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_meters', function (Blueprint $table) {
            $table->id();

            // e.g. "Maduya (2)" — CvSU Carmona has more than one water
            // connection/meter, real bills show at least two distinct
            // accounts billed the same period.
            $table->string('label');

            $table->string('account_no')->nullable();
            $table->string('service_no')->nullable();
            $table->string('meter_no')->nullable();
            $table->string('meter_brand')->nullable();

            $table->boolean('is_active')->default(true);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_meters');
    }
};

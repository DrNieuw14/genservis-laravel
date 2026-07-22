<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_issuance_photos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_issuance_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('path');

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_issuance_photos');
    }
};

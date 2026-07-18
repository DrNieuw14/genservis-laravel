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
        Schema::create('job_request_personnel', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_request_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('personnel_id')
                ->constrained('personnel')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['job_request_id', 'personnel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_request_personnel');
    }
};

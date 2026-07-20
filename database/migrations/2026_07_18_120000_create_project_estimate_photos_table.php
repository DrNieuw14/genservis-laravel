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
        Schema::create('project_estimate_photos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_estimate_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('path');

            // receipt = proof of purchase, work_done = evidence photo,
            // other = anything else worth attaching.
            $table->enum('type', ['receipt', 'work_done', 'other'])
                ->default('other');

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
        Schema::dropIfExists('project_estimate_photos');
    }
};

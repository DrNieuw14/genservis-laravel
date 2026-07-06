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
        Schema::create('procurement_classifications', function (Blueprint $table) {
            $table->id();

            // Government Procurement Classification
            $table->string('part', 100);
            $table->string('main_category', 150);
            $table->string('sub_category_a', 255);
            $table->string('sub_category_b', 255);
            $table->string('sub_category_c', 255);

            // Government Codes
            $table->string('code', 100);
            $table->string('uacs_code', 50);

            // Optional
            $table->text('description')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Prevent duplicate government classifications
            $table->unique([
            'part',
            'main_category',
            'sub_category_a',
            'sub_category_b',
            'sub_category_c',
            'code',
            'uacs_code'
        ], 'procurement_classification_unique');

        // Performance indexes
        $table->index('part');
        $table->index('main_category');
        $table->index('uacs_code');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_classifications');
    }
};

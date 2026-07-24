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
        Schema::create('program_receipt_expenditures', function (Blueprint $table) {

            $table->id();

            // Planning Year
            $table->year('year');

            // Grand Total Projected Income (all funding sources)
            $table->decimal('total_projected_income', 15, 2)->default(0);

            $table->enum('status', [
                'Draft',
                'Approved',
            ])->default('Draft');

            $table->foreignId('prepared_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->unique('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_receipt_expenditures');
    }
};

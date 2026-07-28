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
        Schema::create('pre_utilization_entries', function (Blueprint $table) {

            $table->id();

            // Manual utilization for Personal Services lines specifically —
            // MOOE/Capital Outlay utilization comes from Approved/Completed
            // Purchase Requests instead, this table is never used for those.
            $table->foreignId('pre_allocation_line_id')
                ->constrained('pre_allocation_lines')
                ->cascadeOnDelete();

            $table->decimal('amount', 15, 2);

            $table->date('entry_date');

            $table->string('note')->nullable();

            $table->foreignId('recorded_by')
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
        Schema::dropIfExists('pre_utilization_entries');
    }
};

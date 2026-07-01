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
        Schema::create('procurement_plans', function (Blueprint $table) {

            $table->id();

            // PPMP Number
            $table->string('plan_number')->unique();

            // Planning Year
            $table->year('year');

            // Department preparing the PPMP
            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();

            // Budget Information
            $table->decimal('allocated_budget', 15, 2)->default(0);
            $table->decimal('approved_budget', 15, 2)->default(0);
            $table->decimal('remaining_budget', 15, 2)->default(0);

            // Workflow Status
            $table->enum('status', [
                'Draft',
                'Submitted',
                'Reviewed',
                'Approved',
                'Rejected',
                'Archived'
            ])->default('Draft');

            // Users
            $table->foreignId('prepared_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Notes
            $table->text('remarks')->nullable();

            // Workflow Dates
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_plans');
    }
};
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
        Schema::create('purchase_requests', function (Blueprint $table) {

            $table->id();

            $table->string('pr_number')->unique();

            $table->foreignId('plan_id')
                ->constrained('procurement_plans')
                ->cascadeOnDelete();

            $table->date('pr_date');

            $table->text('purpose')->nullable();

            $table->enum('status', [
                'Draft',
                'Approved',
                'Completed',
                'Rejected',
            ])->default('Draft');

            $table->foreignId('requested_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};

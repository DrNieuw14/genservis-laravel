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
        Schema::create('job_requests', function (Blueprint $table) {
            $table->id();

            $table->string('reference_no')->unique();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('personnel_id')
                ->nullable()
                ->constrained('personnel')
                ->nullOnDelete();

            $table->string('requesting_party');

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            $table->string('office_unit_project');

            // physical_plant = rehabilitation/repair of school infrastructure,
            // approved by Physical Plant and Services.
            // utility = general help (moving furniture, cleaning), approved
            // by the General Services Officer.
            $table->enum('category', ['physical_plant', 'utility']);

            $table->string('nature_of_request');
            $table->text('work_summary');
            $table->string('work_category')->nullable();

            $table->date('target_date')->nullable();

            $table->string('status')->default('pending');
            // pending -> approved -> assigned -> completed
            //         -> rejected
            //         -> cancelled

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->text('rejection_reason')->nullable();

            $table->text('remarks')->nullable();

            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('assigned_at')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_requests');
    }
};

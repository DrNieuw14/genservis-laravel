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
        Schema::create('walkin_requests', function (Blueprint $table) {

            $table->id();

            $table->string('reference_no')->unique();

            $table->string('requestor_name');

            $table->foreignId('personnel_id')
                ->nullable()
                ->constrained('personnel')
                ->nullOnDelete();

            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();

            $table->string('room');

            $table->string('purpose');

            $table->enum('priority', [
                'Normal',
                'Urgent'
            ])->default('Normal');

            $table->text('remarks')->nullable();

            $table->foreignId('issued_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('status')
                ->default('Issued');

            $table->timestamp('issued_at')->nullable();

            $table->timestamp('printed_at')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->string('transaction_type')
            ->default('WALK-IN ISSUE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walkin_requests');
    }
};

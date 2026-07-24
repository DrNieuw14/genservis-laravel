<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sports_equipment_borrows', function (Blueprint $table) {
            $table->id();

            // e.g. "SEB-2026-0001" — same numbering convention as
            // Material Request's "MR-YYYY-XXXX".
            $table->string('borrow_number')->unique();

            $table->foreignId('sports_equipment_id')
                ->constrained('sports_equipments')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('department_id')
                ->constrained('departments')
                ->restrictOnDelete();

            $table->integer('quantity');

            $table->string('room')->nullable();

            $table->text('purpose');

            $table->date('expected_return_date');

            $table->date('actual_return_date')->nullable();

            // pending -> approved (equipment now out) -> returned
            //         -> rejected
            $table->string('status')->default('pending');

            // Good | Damaged | For Repair | Unserviceable — same vocabulary
            // as PropertyItem::CONDITIONS, captured when the custodian logs
            // the physical return.
            $table->string('condition_on_return')->nullable();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->foreignId('returned_confirmed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('rejection_reason')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sports_equipment_borrows');
    }
};

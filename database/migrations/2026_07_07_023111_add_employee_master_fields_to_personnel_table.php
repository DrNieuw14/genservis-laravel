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
        Schema::table('personnel', function (Blueprint $table) {

            // Employment Type
            $table->foreignId('employment_type_id')
                ->nullable()
                ->after('employee_id')
                ->constrained('employment_types')
                ->nullOnDelete();

            // Organizational Unit (existing departments table)
            $table->foreignId('department_id')
                ->nullable()
                ->after('employment_type_id')
                ->constrained('departments')
                ->nullOnDelete();

            // Position
            $table->foreignId('position_id')
                ->nullable()
                ->after('department_id')
                ->constrained('positions')
                ->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnel', function (Blueprint $table) {

            $table->dropForeign(['employment_type_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['position_id']);

            $table->dropColumn([
                'employment_type_id',
                'department_id',
                'position_id',
            ]);

        });
    }
};
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
        if (! Schema::hasColumn('procurement_plan_items', 'created_by')) {
            Schema::table('procurement_plan_items', function (Blueprint $table) {
                $table->foreignId('created_by')
                    ->nullable()
                    ->after('remarks')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('procurement_plan_items', 'created_by')) {
            Schema::table('procurement_plan_items', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            });
        }
    }
};

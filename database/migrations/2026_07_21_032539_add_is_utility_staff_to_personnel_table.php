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

            // Explicit, HR-edit-proof membership in Mark's Utility &
            // Maintenance Staff pool. Previously derived by matching
            // position_name against "Utility"/"Maintenance"/etc, which
            // broke the moment HR relabeled someone's official designation
            // (e.g. "Utility Worker" -> "Administrative Aide I") even
            // though they still work under Mark for attendance purposes.
            $table->boolean('is_utility_staff')->default(false)->after('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnel', function (Blueprint $table) {
            $table->dropColumn('is_utility_staff');
        });
    }
};

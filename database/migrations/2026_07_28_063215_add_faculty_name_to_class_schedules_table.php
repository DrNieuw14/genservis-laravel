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
        Schema::table('class_schedules', function (Blueprint $table) {

            // Plain-text fallback for a real transcribed instructor label
            // ("MR DALISAY") that doesn't confidently match an existing
            // Personnel record — title+surname only, no first name or
            // employee ID, so auto-matching risked linking the wrong real
            // person. personnel_id stays the correct way to assign faculty
            // for entries added through the live UI going forward.
            $table->string('faculty_name')->nullable()->after('personnel_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropColumn('faculty_name');
        });
    }
};

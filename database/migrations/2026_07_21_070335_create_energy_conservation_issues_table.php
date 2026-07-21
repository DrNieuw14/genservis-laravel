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
        Schema::create('energy_conservation_issues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('energy_conservation_report_id');
            $table->foreign('energy_conservation_report_id', 'fk_eca_issues_report')
                ->references('id')->on('energy_conservation_reports')
                ->cascadeOnDelete();

            $table->text('issue_concern');
            $table->text('action_taken')->nullable();
            $table->text('recommendation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('energy_conservation_issues');
    }
};

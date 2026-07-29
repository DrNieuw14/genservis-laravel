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
        Schema::create('thesis_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_advisee_id')->constrained('thesis_advisees')->cascadeOnDelete();
            $table->enum('direction', ['in', 'out']);
            $table->string('chapter_stage');
            $table->date('moved_at');
            $table->text('remarks')->nullable();
            $table->foreignId('logged_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_movements');
    }
};

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
    Schema::create('users', function (Blueprint $table) {
        $table->id();

        $table->string('name');
        $table->string('email')->unique();
        $table->string('username')->unique(); // ✅ ADD THIS
        $table->string('password');

        $table->date('birthdate')->nullable(); // ✅ ADD
        $table->string('birth_month')->nullable(); // ✅ ADD
        $table->integer('age')->nullable(); // ✅ ADD

        $table->string('role')->default('personnel'); // ✅ ADD
        $table->string('status')->default('pending'); // ✅ ADD

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

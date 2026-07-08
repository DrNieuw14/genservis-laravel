<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_contacts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('personnel_id')
            ->constrained('personnel')
            ->cascadeOnDelete();

            $table->string('primary_email');

            $table->string('alternate_email')->nullable();

            $table->string('mobile_number', 20);

            $table->string('telephone_number', 20)->nullable();

            $table->string('emergency_contact_person');

            $table->string('emergency_contact_number', 20);

            $table->string('emergency_relationship');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_contacts');
    }
};

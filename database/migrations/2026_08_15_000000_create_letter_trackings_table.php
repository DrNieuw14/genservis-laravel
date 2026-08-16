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
        Schema::create('letter_trackings', function (Blueprint $table) {
            $table->id();

            $table->string('control_no')->unique();

            // incoming = received by HR from another office/party;
            // outgoing = prepared by HR/campus for sending out. Both kinds
            // may need to pass through the Campus Administrator for signature.
            $table->enum('direction', ['incoming', 'outgoing']);

            $table->string('subject');
            $table->string('from_office')->nullable();
            $table->string('to_office')->nullable();

            $table->date('date_received')->nullable();
            $table->date('forwarded_at')->nullable();
            $table->date('signed_at')->nullable();
            $table->date('released_at')->nullable();

            $table->string('status')->default('logged');
            $table->text('remarks')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_trackings');
    }
};

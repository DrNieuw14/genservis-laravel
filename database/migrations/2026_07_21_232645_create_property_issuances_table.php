<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_issuances', function (Blueprint $table) {
            $table->id();

            // Which room's inventory the issued items were drawn from —
            // purely for context/traceability, the actual issued items are
            // snapshotted onto property_issuance_items.
            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            // ics_5k_below | ics_mid | par — decided once at creation from
            // the selected items' unit cost (real COA value brackets), then
            // fixed for good so a later cost edit on the source item never
            // silently reclassifies an already-issued slip.
            $table->string('form_type');

            // e.g. "ICS-2026-0001" / "PAR-2026-0001" — ICS shares one
            // numbering series across both value brackets (matches how the
            // real ICS No. field works), PAR has its own separate series.
            $table->string('slip_no')->unique();

            $table->string('fund_cluster')->nullable();

            // ICS-only field on the real form; simply left blank on a PAR.
            $table->string('po_number')->nullable();

            $table->foreignId('recipient_personnel_id')
                ->constrained('personnel')
                ->restrictOnDelete();

            $table->foreignId('issued_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('issued_at');

            $table->text('remarks')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_issuances');
    }
};

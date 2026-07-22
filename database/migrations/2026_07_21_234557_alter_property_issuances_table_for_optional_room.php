<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Real ICS/PAR slips never reference a room — they're issued to a
        // person, not housed anywhere — so room_id becomes purely optional
        // context rather than a hard requirement. No doctrine/dbal in this
        // project, so drop/recreate the FK with raw SQL instead of ->change().
        Schema::table('property_issuances', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
        });

        DB::statement('ALTER TABLE property_issuances MODIFY room_id BIGINT UNSIGNED NULL');

        Schema::table('property_issuances', function (Blueprint $table) {
            $table->foreign('room_id')->references('id')->on('rooms')->nullOnDelete();

            // Fallback for historical/external issuing officers who have no
            // User account in this system (e.g. a past Supply Officer) —
            // issued_by (the FK) is preferred when the issuer is a real
            // system user, these are only read when it's null.
            $table->string('issued_by_name')->nullable()->after('issued_by');
            $table->string('issued_by_position')->nullable()->after('issued_by_name');
        });
    }

    public function down(): void
    {
        Schema::table('property_issuances', function (Blueprint $table) {
            $table->dropColumn(['issued_by_name', 'issued_by_position']);
            $table->dropForeign(['room_id']);
        });

        DB::statement('ALTER TABLE property_issuances MODIFY room_id BIGINT UNSIGNED NOT NULL');

        Schema::table('property_issuances', function (Blueprint $table) {
            $table->foreign('room_id')->references('id')->on('rooms')->cascadeOnDelete();
        });
    }
};

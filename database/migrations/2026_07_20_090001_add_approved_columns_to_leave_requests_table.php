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
        Schema::table('leave_requests', function (Blueprint $table) {

            // The model's $fillable already listed these (pre-existing),
            // but the original migration never actually created them —
            // the old LeaveController::approve()/reject() only ever set
            // 'status', so the gap never surfaced until this write path.
            if (!Schema::hasColumn('leave_requests', 'approved_by')) {
                $table->foreignId('approved_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('leave_requests', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn('approved_at');
        });
    }
};

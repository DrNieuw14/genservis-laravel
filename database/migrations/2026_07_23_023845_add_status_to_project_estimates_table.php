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
        Schema::table('project_estimates', function (Blueprint $table) {
            $table->enum('status', ['ongoing', 'done'])->default('ongoing')->after('exclusions');
            $table->timestamp('status_updated_at')->nullable()->after('status');
            $table->foreignId('status_updated_by')->nullable()->after('status_updated_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_estimates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('status_updated_by');
            $table->dropColumn(['status', 'status_updated_at']);
        });
    }
};

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
        Schema::create('pre_allocations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pre_id')
                ->constrained('program_receipt_expenditures')
                ->cascadeOnDelete();

            // Fund Source (F164 = regular income fund, F101 = Trust/Misc)
            $table->enum('fund_source', ['164', '101'])->default('164');

            // Program/Project Activity (PPA/MFO)
            $table->enum('ppa', [
                'GASS',
                'STO',
                'MFO1',
                'MFO2',
                'MFO3',
                'MFO4',
            ]);

            $table->decimal('personal_services', 15, 2)->default(0);
            $table->decimal('mooe', 15, 2)->default(0);
            $table->decimal('capital_outlay', 15, 2)->default(0);
            $table->decimal('infrastructure', 15, 2)->default(0);

            $table->timestamps();

            $table->unique(['pre_id', 'fund_source', 'ppa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_allocations');
    }
};

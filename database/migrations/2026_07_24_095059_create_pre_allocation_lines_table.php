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
        Schema::create('pre_allocation_lines', function (Blueprint $table) {

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

            // The "Allotment Class" grouping from the source document
            $table->enum('allotment_class', [
                'Personal Services',
                'Maintenance and Other Operating Expenses',
                'Capital Outlay',
            ]);

            $table->string('uacs_code', 20);

            $table->string('description');

            $table->decimal('amount', 15, 2)->default(0);

            $table->timestamps();

            $table->index(['pre_id', 'ppa', 'uacs_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_allocation_lines');
    }
};

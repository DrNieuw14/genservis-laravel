<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('material_requests', function ($table) {

            $table->unsignedBigInteger('released_by')
                  ->nullable()
                  ->after('remarks');

            $table->timestamp('released_at')
                  ->nullable()
                  ->after('released_by');
        });
    }

    public function down()
    {
        Schema::table('material_requests', function ($table) {

            $table->dropColumn([
                'released_by',
                'released_at'
            ]);

        });
    }
};
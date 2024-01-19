<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTurnoCajaIdToSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('turno_caja_id')->nullable();
            $table->foreign('turno_caja_id')->references('id')->on('turno_cajas')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['turno_caja_id']);
            $table->dropColumn('turno_caja_id');
        });
    }
}


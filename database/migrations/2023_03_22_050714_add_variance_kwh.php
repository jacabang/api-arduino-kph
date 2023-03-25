<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVarianceKwh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('socket_reading', function (Blueprint $table) {
            //
            $table->float('variance_kwh', 15, 4)->after('kwh');
            $table->float('kwph', 15, 4)->after('variance_kwh');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('socket_reading', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocketReading extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socket_reading', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('socket_id')->unsigned();
            $table->foreign('socket_id')->references('id')->on('socket');
            $table->float('kwh', 15, 4);
            $table->date('treg');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socket_reading');
    }
}

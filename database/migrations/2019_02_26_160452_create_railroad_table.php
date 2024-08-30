<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRailroadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('railroads', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('order_id');

            // DATA RETIRADA VAZIO
            $table->dateTime('withdrawal_date');
            // Data inicio
            $table->dateTime('start_date');
            // DATA CHEGADA
            $table->dateTime('arrival_date');
            // Data final
            $table->dateTime('final_date');
            // INICIO RETIRADA
            $table->dateTime('withdrawal_start_date');

            // TEMPO ESTIMADO VIAGEM
            $table->string('estimated_time');

            $table->string('loading_location');
            
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('railroads');
    }
}

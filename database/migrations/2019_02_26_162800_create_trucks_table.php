<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('railroad_id');

            // Nome do motorista do veículo
            $table->string('driver')->nullable();
            
            // Placa do veículo
            $table->string('plate');

            // Telefone de contato do motorista
            $table->string('phone')->nullable();

            // NFE da remessa
            $table->string('nfe')->nullable();

            // Peso da carga (KG)
            $table->string('wheight');

            // Previsão da data de estufagem
            $table->datetime('stove_forecasting');

            // Horario do agendamento
            $table->datetime('time_scheduling');

            $table->foreign('railroad_id')->references('id')->on('railroads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trucks');
    }
}

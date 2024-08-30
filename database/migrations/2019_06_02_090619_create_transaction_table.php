<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->unsignedInteger('transaction_type_id'); // Tipo de transação
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
            
            $table->integer('value'); // Valor

            $table->string('bank')->nullable(); // Banco
            $table->text('description')->nullable(); // Descrição
            $table->string('dollar_value')->nullable(); // Valor do dolar
            $table->integer('converted_value')->nullable(); // Valor convertido
            $table->string('cambio_contract')->nullable(); // Contrato de cambio
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}

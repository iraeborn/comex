<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersDocumentOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orders_document_order')) {
            Schema::create('orders_document_order', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('orders_id')->unsigned();
                $table->integer('documents_id')->unsigned();
                $table->foreign('orders_id')->references('id')->on('orders');
                $table->foreign('documents_id')->references('id')->on('documents');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_document_order');
    }
}

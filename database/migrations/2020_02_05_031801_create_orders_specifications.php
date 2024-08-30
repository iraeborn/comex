<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersSpecifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orders_specifications')) {
            Schema::create('orders_specifications', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('orders_id')->unsigned();
                $table->integer('specifications_id')->unsigned();
                $table->foreign('orders_id')->references('id')->on('orders');
                $table->foreign('specifications_id')->references('id')->on('specifications');
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
        Schema::dropIfExists('orders_specifications');
    }
}

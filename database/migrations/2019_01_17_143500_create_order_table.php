<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            /*$table->text('value');
            
            $table->text('description');
            $table->integer('crop');
            $table->integer('quantity');
            $table->integer('unity_price');
            $table->integer('total_bags');
            $table->integer('net_weight');
            $table->integer('gross_weight');*/

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('owner_id');
            
            $table->integer('swift_advance');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

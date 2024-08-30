<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('order_id');
            $table->unsignedInteger('shipping_status_id')->nullable();
            $table->unsignedInteger('loading_port_id')->nullable();
            $table->unsignedInteger('discharge_port_id')->nullable();

            $table->string('booking')->nullable();
            $table->string('vessel')->nullable();
            $table->string('shipping_line')->nullable();
            $table->string('free_time_destination')->nullable();

            $table->integer('quantity_containers')->nullable();

            $table->dateTime('etd')->nullable();
            $table->dateTime('eta')->nullable();

            $table->text('reason')->nullable()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shippings');
    }
}

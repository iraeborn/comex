<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContainerStuffingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_stuffings', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->string('terminal')->nullable();
            $table->string('terminal_user')->nullable(); // Terminal User

            $table->integer('weight')->nullable();
            $table->integer('number_of_bags')->nullable();

            $table->datetime('empty_release_for_outbound_date')->nullable(); // Empty Release for Outbound Date
            $table->datetime('stuffing_starting_date')->nullable(); // Stuffing Starting Date
            $table->datetime('stuffing_ending_date')->nullable(); // Stuffing Ending Date
            
            $table->text('terminal_observations')->nullable(); // Terminal Observations
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('container_stuffings');
    }
}

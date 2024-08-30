<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoadingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loadings', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            // Start of Truck Loading Date
            $table->timestamp('start_truck_loading_date')->nullable();

            // End of Truck Loading Date
            $table->timestamp('end_truck_loading_date')->nullable();

            // Loading Location
            $table->string('loading_location')->nullable();

            // Unloading Location
            $table->string('unloading_location')->nullable();

            // Estimated Transit Time (DD)
            $table->string('transit_time')->nullable();

            // Quantity of Vehicles Hired
            // $table->string('quantity_vehicles_hired');
        });

        Schema::table('trucks', function (Blueprint $table) {
            $table->unsignedInteger('loadings_id')->nullable();
            $table->foreign('loadings_id')->references('id')->on('loadings')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropForeign('trucks_loadings_id_foreign');
            $table->dropColumn('loadings_id');
        });

        Schema::dropIfExists('loadings');
    }
}

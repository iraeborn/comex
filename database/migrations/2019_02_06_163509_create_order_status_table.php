<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_status', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('name');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('order_status_id')->after('swift_advance')->default(1);
            $table->foreign('order_status_id')->references('id')->on('order_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_order_status_id_foreign');
            $table->dropColumn('order_status_id');
        });
        Schema::dropIfExists('order_status');
    }
}

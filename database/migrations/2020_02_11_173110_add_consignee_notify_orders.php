<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConsigneeNotifyOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('orders', ['consignee_id', 'notify_id', 'consignee_id', 'notify_id'])) {
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('consignee_id')->unsigned()->nullable();
                $table->integer('notify_id')->unsigned()->nullable();
                $table->foreign('consignee_id')->references('id')->on('users');
                $table->foreign('notify_id')->references('id')->on('users');
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
        Schema::table('orders', function (Blueprint $table) {
            Schema::dropIfExists('consignee_id');
            Schema::dropIfExists('notify_id');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankIdToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('orders', ['banks_id', 'banks_id'])) {
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('banks_id')->unsigned()->nullable();
                $table->foreign('banks_id')->references('id')->on('banks');
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
            $table->dropForeign('orders_banks_id_foreign');
            $table->dropColumn('banks_id');
        });
    }
}

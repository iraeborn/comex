<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackingToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('packing')->nullable();
                $table->string('incoterm')->nullable();
                $table->text('payment_conditions')->nullable();
                $table->string('port_origin')->nullable();
                $table->string('port_destiny')->nullable();
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
            $table->dropColumn('packing');
            $table->dropColumn('incoterm');
            $table->dropColumn('payment_conditions');
            $table->dropColumn('port_origin');
            $table->dropColumn('port_destiny');
        });
    }
}

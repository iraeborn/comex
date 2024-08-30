<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceFildsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('invoce_advance_status')->nullable(); //adiantamento ()
            $table->integer('invoce_advance_value')->nullable(); //valor do adiantamento
            $table->integer('invoce_balance')->nullable(); //saldo
            $table->integer('invoce_total')->nullable(); // Total
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
            $table->dropColumn('invoce_advance_status');
            $table->dropColumn('invoce_advance_value');
            $table->dropColumn('invoce_balance');
            $table->dropColumn('invoce_total');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBagFieldsToBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->string('bags')->nullable();
            $table->string('damaged')->nullable();
            $table->string('lack')->nullable();
            $table->string('leftovers')->nullable();
            $table->string('physical_balance')->nullable();
            $table->string('total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('bags');
            $table->dropColumn('damaged');
            $table->dropColumn('lack');
            $table->dropColumn('leftovers');
            $table->dropColumn('physical_balance');
            $table->dropColumn('total');
        });
    }
}

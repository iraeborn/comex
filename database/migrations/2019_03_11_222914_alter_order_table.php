<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('fumigation_id')->nullable();
            $table->unsignedInteger('quality_id')->nullable();
            $table->unsignedInteger('weight_id')->nullable();
            $table->unsignedInteger('seguro_id')->nullable();

            $table->foreign('fumigation_id')->references('id')->on('users')->nullable();
            $table->foreign('quality_id')->references('id')->on('users')->nullable();
            $table->foreign('weight_id')->references('id')->on('users')->nullable();
            $table->foreign('seguro_id')->references('id')->on('users')->nullable();
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
            $table->dropForeign(['fumigation_id']);
            $table->dropColumn('fumigation_id');
            $table->dropForeign(['quality_id']);
            $table->dropColumn('quality_id');
            $table->dropForeign(['weight_id']);
            $table->dropColumn('weight_id');
            $table->dropForeign(['seguro_id']);
            $table->dropColumn('seguro_id');
        });
    }
}

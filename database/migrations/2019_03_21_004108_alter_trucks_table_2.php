<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTrucksTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->renameColumn('nfe', 'bill_number');
            $table->renameColumn('time_scheduling', 'truck_unloading_datetime');
            $table->dropColumn('stove_forecasting');
            $table->string('estimated_time');
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
            $table->renameColumn('bill_number', 'nfe');
            $table->renameColumn('truck_unloading_datetime', 'time_scheduling');
            $table->dropColumn('estimated_time');
            $table->datetime('stove_forecasting');
        });

    }
}

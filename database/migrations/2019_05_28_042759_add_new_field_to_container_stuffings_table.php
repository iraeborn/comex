<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldToContainerStuffingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('container_stuffings', function (Blueprint $table) {
            $table->text('depot_pick')->nullable();
            $table->text('qtd_container')->nullable();

            $table->text('quantity_per_container')->nullable();
            $table->text('quantity_total')->nullable();
            $table->text('container_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('container_stuffings', function (Blueprint $table) {
            $table->dropColumn('depot_pick');
            $table->dropColumn('qtd_container');

            $table->dropColumn('quantity_per_container');
            $table->dropColumn('quantity_total');
            $table->dropColumn('container_type');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillOfLadingColumnToMapasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mapas', function (Blueprint $table) {
            !Schema::hasColumn('mapas', 'bill_of_lading') ?? $table->string('bill_of_lading')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mapas', function (Blueprint $table) {
            $table->dropColumn('bill_of_lading');
        });
    }
}

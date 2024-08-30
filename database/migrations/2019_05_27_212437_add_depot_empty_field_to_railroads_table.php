<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepotEmptyFieldToRailroadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('railroads', function (Blueprint $table) {
            $table->text('depot_empty')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('railroads', function (Blueprint $table) {
            $table->dropColumn('depot_empty');
        });
    }
}

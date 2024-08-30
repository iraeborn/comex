<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToMapasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mapas', function (Blueprint $table) {
            $table->string('due_code')->nullable();
            $table->string('ruc_code')->nullable();
            $table->string('access_key')->nullable();
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
            $table->dropColumn('due_code');
            $table->dropColumn('ruc_code');
            $table->dropColumn('access_key');
        });
    }
}

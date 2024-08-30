<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToFumigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fumigations', function (Blueprint $table) {
            $table->string('treatment')->nullable();
            $table->string('exposition_time')->nullable();
            $table->string('temperature_local')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fumigations', function (Blueprint $table) {
            $table->dropColumn('treatment');
            $table->dropColumn('exposition_time');
            $table->dropColumn('temperature_local');
        });
    }
}

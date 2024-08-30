<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInRailroadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('railroads', function (Blueprint $table) {
            $table->dateTime('withdrawal_date')->nullable()->change();
            $table->dateTime('start_date')->nullable()->change();
            $table->dateTime('arrival_date')->nullable()->change();
            $table->dateTime('final_date')->nullable()->change();
            $table->dateTime('withdrawal_start_date')->nullable()->change();

            $table->string('estimated_time')->nullable()->change();
            $table->string('loading_location')->nullable()->change();
            $table->string('transfer_terminal_date')->nullable()->change();
            $table->string('terminal_confirmation_date')->nullable()->change();
            $table->string('freetime_date')->nullable()->change();
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
        });
    }
}

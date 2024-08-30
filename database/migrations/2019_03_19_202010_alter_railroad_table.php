<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRailroadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('railroads', function (Blueprint $table) {
            $table->timestamp('transfer_terminal_date')->useCurrent = true;
            $table->timestamp('terminal_confirmation_date')->useCurrent = true;
            $table->timestamp('freetime_date')->useCurrent = true;
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
            $table->dropColumn('transfer_terminal_date');
            $table->dropColumn('terminal_confirmation_date');
            $table->dropColumn('freetime_date');
        });
    }
}

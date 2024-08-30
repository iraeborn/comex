<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInformationToContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('containers', function (Blueprint $table) {
            $table->string('nfe_balance')->nullable();
            $table->string('failure')->nullable();
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
        Schema::table('containers', function (Blueprint $table) {
            $table->dropColumn('nfe_balance');
            $table->dropColumn('failure');
            $table->dropColumn('lack');
            $table->dropColumn('leftovers');
            $table->dropColumn('physical_balance');
            $table->dropColumn('total');
        });
    }
}

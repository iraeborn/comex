<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('containers', function (Blueprint $table) {
            !Schema::hasColumn('containers', 'cbm') ?? $table->integer('cbm')->nullable();
            !Schema::hasColumn('containers', 'release_date') ?? $table->dateTime('release_date')->nullable();
            !Schema::hasColumn('containers', 'withdrawal_date') ?? $table->dateTime('withdrawal_date')->nullable();
            !Schema::hasColumn('containers', 'return_date') ?? $table->dateTime('return_date')->nullable();
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
            $table->dropColumn('cbm');
            $table->dropColumn('release_date');
            $table->dropColumn('withdrawal_date');
            $table->dropColumn('return_date');
        });
    }
}

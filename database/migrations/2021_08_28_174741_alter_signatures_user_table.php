<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSignaturesUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable();
            $table->longText('term1')->nullable();
            $table->longText('term2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        
        Schema::table('signatures', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('term1');
            $table->dropColumn('term2');
        });
    }
}

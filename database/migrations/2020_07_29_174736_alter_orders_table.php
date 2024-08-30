<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            !Schema::hasColumn('orders', 'finished') ?? $table->boolean('finished')->default(false);
            !Schema::hasColumn('orders', 'bill_of_lading') ?? $table->string('bill_of_lading')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('finished');
            $table->dropColumn('bill_of_lading');
        });
    }
}

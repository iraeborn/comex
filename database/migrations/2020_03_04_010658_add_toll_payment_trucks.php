<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTollPaymentTrucks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trucks', function (Blueprint $table) {
            !Schema::hasColumn('trucks', 'toll_payment') ?? $table->integer('toll_payment')->nullable();
            !Schema::hasColumn('trucks', 'note') ?? $table->text('note')->nullable();
            !Schema::hasColumn('trucks', 'tara_horse') ?? $table->integer('tara_horse')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropColumn('toll_payment');
            $table->dropColumn('note');
            $table->dropColumn('tara_horse');
        });
    }
}

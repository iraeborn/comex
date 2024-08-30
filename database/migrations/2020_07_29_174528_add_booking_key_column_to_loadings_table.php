<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookingKeyColumnToLoadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loadings', function (Blueprint $table) {
            !Schema::hasColumn('loadings', 'booking_key') ?? $table->string('booking_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loadings', function (Blueprint $table) {
            $table->dropColumn('booking_key');
        });
    }
}

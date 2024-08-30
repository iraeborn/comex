<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shippings', function (Blueprint $table) {
            !Schema::hasColumn('shippings', 'freight') ?? $table->string('freight')->nullable();
            !Schema::hasColumn('shippings', 'free_time_origin') ?? $table->string('free_time_origin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shippings', function (Blueprint $table) {
            $table->dropColumn('freight');
            $table->dropColumn('free_time_origin');
        });
    }
}

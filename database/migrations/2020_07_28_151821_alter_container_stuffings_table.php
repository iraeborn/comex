<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContainerStuffingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('container_stuffings', function (Blueprint $table) {
            !Schema::hasColumn('container_stuffings', 'dispatch_place_name') ?? $table->string('dispatch_place_name')->nullable();
            !Schema::hasColumn('container_stuffings', 'dispatch_place_code') ?? $table->string('dispatch_place_code')->nullable();
            !Schema::hasColumn('container_stuffings', 'containers_return_date') ?? $table->dateTime('containers_return_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('container_stuffings', function (Blueprint $table) {
            $table->dropColumn('dispatch_place_name');
            $table->dropColumn('dispatch_place_code');
            $table->dropColumn('containers_return_date');
        });
    }
}

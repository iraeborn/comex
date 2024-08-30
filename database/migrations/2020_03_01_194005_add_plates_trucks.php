<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlatesTrucks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trucks', function (Blueprint $table) {
            !Schema::hasColumn('trucks', 'plate_two') ?? $table->string('plate_two')->nullable();
            !Schema::hasColumn('trucks', 'plate_three') ?? $table->string('plate_three')->nullable();
            !Schema::hasColumn('trucks', 'plate_four') ?? $table->string('plate_four')->nullable();
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
            $table->dropColumn('plate_two');
            $table->dropColumn('plate_three');
            $table->dropColumn('plate_four');
        });
    }
}

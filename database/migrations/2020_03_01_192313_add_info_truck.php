<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoTruck extends Migration
{

    public function up()
    {
        Schema::table('trucks', function (Blueprint $table) {
            !Schema::hasColumn('trucks', 'name_document') ?? $table->string('name_document')->nullable();
            !Schema::hasColumn('trucks', 'tara_truck_one') ?? $table->integer('tara_truck_one')->nullable();
            !Schema::hasColumn('trucks', 'tara_truck_two') ?? $table->integer('tara_truck_two')->nullable();
            !Schema::hasColumn('trucks', 'tara_bags') ?? $table->double('tara_bags')->nullable();
        });
    }

    public function down()
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropColumn('name_document');
            $table->dropColumn('tara_truck_one');
            $table->dropColumn('tara_truck_two');
            $table->dropColumn('tara_bags');
        });
    }
}

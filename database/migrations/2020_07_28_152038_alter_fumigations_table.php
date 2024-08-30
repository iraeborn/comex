<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFumigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fumigations', function (Blueprint $table) {
            !Schema::hasColumn('fumigations', 'dosage') ?? $table->string('dosage')->nullable();
            !Schema::hasColumn('fumigations', 'place_of_treatment') ?? $table->text('place_of_treatment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fumigations', function (Blueprint $table) {
            $table->dropColumn('dosage');
            $table->dropColumn('place_of_treatment');
        });
    }
}

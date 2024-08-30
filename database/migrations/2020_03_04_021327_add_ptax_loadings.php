<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPtaxLoadings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loadings', function (Blueprint $table) {
            !Schema::hasColumn('loadings', 'date_ptax') ?? $table->date('date_ptax')->nullable();
            !Schema::hasColumn('loadings', 'tax_ptax') ?? $table->double('tax_ptax')->nullable();
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
            $table->dropColumn('date_ptax');
            $table->dropColumn('tax_ptax');
        });
    }
}

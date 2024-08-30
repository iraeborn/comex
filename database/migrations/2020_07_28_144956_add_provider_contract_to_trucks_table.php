<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProviderContractToTrucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('trucks', ['provider_contract_id', 'provider_contract_id'])) {
            Schema::table('trucks', function (Blueprint $table) {
                $table->unsignedInteger('provider_contract_id')->nullable();
                $table->foreign('provider_contract_id')->references('id')->on('provider_contracts')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropForeign('trucks_provider_contract_id_foreign');
            $table->dropColumn('provider_contract_id');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTruckToContractProvisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('contract_provisions', ['truck_id', 'truck_id'])) {
            Schema::table('contract_provisions', function (Blueprint $table) {
                $table->unsignedInteger('truck_id')->nullable();
                $table->foreign('truck_id')->references('id')->on('trucks');
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
        Schema::table('contract_provisions', function (Blueprint $table) {
            $table->dropForeign('contract_provisions_truck_id_foreign');
            $table->dropColumn('truck_id');
        });
    }
}

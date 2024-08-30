<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('contract_services')) {
            Schema::create('contract_services', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('provider_contract_id')->unsigned();
                $table->string('description');
                $table->integer('billing_factor_type')->unsigned();
                $table->string('currency_type');
                $table->integer('billing_value');
                $table->timestamps();

                $table->foreign('provider_contract_id')
                    ->references('id')
                    ->on('provider_contracts');
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
        Schema::dropIfExists('contract_services');
    }
}

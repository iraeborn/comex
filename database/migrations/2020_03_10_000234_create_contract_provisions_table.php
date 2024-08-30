<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractProvisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('contract_provisions')) {
            Schema::create('contract_provisions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('provider_contract_id')->unsigned();
                $table->integer('order_id')->unsigned();
                $table->integer('budgeted_amount')->nullable();
                $table->integer('effective_amount')->nullable();
                $table->string('currency_type')->nullable();
                $table->tinyInteger('status');
                $table->integer('service_provision_id')->unsigned()->nullable();
                $table->timestamps();

                $table->foreign('provider_contract_id')
                    ->references('id')
                    ->on('provider_contracts');

                $table->foreign('order_id')
                    ->references('id')
                    ->on('orders');
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
        Schema::dropIfExists('contract_provisions');
    }
}

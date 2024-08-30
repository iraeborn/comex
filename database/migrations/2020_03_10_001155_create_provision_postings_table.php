<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvisionPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('provision_postings')) {
            Schema::create('provision_postings', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('contract_provision_id')->unsigned();
                $table->string('invoice_id');
                $table->integer('invoice_amount');
                $table->integer('invoice_amount_converted');
                $table->integer('currency_fee')->default(1);
                $table->string('currency_type');
                $table->date('due_date');
                $table->timestamps();

                $table->foreign('contract_provision_id')
                    ->references('id')
                    ->on('contract_provisions');
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
        Schema::dropIfExists('provision_postings');
    }
}

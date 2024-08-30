<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('provider_contracts')) {
            Schema::create('provider_contracts', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('provider_id')->unsigned();
                $table->string('identifier');
                $table->string('service_type');
                $table->string('service_location');
                $table->string('negotiated_terms');
                $table->tinyInteger('is_active');
                $table->dateTime('expirated_at');
                $table->timestamps();

                $table->foreign('provider_id')
                    ->references('id')
                    ->on('users');
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
        Schema::dropIfExists('provider_contracts');
    }
}

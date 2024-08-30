<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContainerObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('container_observations')) {
            Schema::create('container_observations', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('container_id');
                $table->foreign('container_id')->references('id')->on('container_stuffings');
                $table->unsignedInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');
                $table->text('text');
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('container_observations');
    }
}

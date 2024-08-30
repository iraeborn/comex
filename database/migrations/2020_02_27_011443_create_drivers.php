<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrivers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('items')) {
            Schema::create('drivers', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('cnh')->nullable()->unique();
                $table->string('rg')->nullable()->unique();
                $table->string('cpf')->nullable()->unique();
                $table->date('born_at')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('drivers');
    }
}

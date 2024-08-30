<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_email', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('status')->default(0);
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->text('recipients')->nullable();
            $table->string('subject')->nullable();
            $table->longtext('message')->nullable();
            $table->longtext('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        
        Schema::dropIfExists('log_email');
    }
}

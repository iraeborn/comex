<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_logs', function (Blueprint $table) {
            $table->id();
            $table->json('log')->nullable();
            $table->unsignedInteger('causer_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->unsignedInteger('subject_id')->nullable();
            $table->timestamps();

            $table->foreign('causer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_logs');
    }
}

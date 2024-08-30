<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('banks')) {
            Schema::create('banks', function (Blueprint $table) {
                $table->increments('id');
                $table->string('intermediary_bank')->nullable();
                $table->string('beneficiary_bank')->nullable();
                $table->string('beneficiary_name')->nullable();
                $table->string('iban')->nullable();
                $table->string('swift_code')->nullable();
                $table->string('branch_number')->nullable();
                $table->string('local')->nullable();
                $table->string('account_nr_one')->nullable();
                $table->string('account_nr_two')->nullable();
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
        Schema::dropIfExists('banks');
    }
}

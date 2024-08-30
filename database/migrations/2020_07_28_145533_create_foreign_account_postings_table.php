<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignAccountPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('foreign_account_postings')) {
            Schema::create('foreign_account_postings', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('supplier_id')->nullable();
                $table->foreign('supplier_id')->references('id')->on('users')->onDelete('set null');
                $table->unsignedInteger('provision_posting_id')->nullable();
                $table->foreign('provision_posting_id')->references('id')->on('provision_postings')->onDelete('cascade');
                $table->string('invoice_id')->nullable();
                $table->string('description')->nullable();
                $table->integer('invoice_amount');
                $table->integer('invoice_amount_converted');
                $table->integer('currency_fee')->default(1);
                $table->string('currency_type');
                $table->date('due_date');
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
        Schema::dropIfExists('foreign_account_postings');
    }
}

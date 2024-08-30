<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOriginalDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('original_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('order_id');
            $table->unsignedInteger('original_documents_status_id');
            $table->unsignedInteger('original_documents_type_id');

            $table->string('custom_type')->nullable();
            $table->string('url')->nullable();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('original_documents_status_id')->references('id')->on('original_documents_status');
            $table->foreign('original_documents_type_id')->references('id')->on('original_documents_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('original_documents');
    }
}

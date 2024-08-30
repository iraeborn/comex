<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('url');

            $table->text('reason')->nullable();

            $table->unsignedInteger('order_id');
            $table->unsignedInteger('document_type_id');
            $table->unsignedInteger('document_status_id');

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('document_type_id')->references('id')->on('document_type');
            $table->foreign('document_status_id')->references('id')->on('document_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}

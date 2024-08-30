<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDocumentsOrdersDocumentOrder extends Migration
{

    public function up()
    {
        Schema::table('orders_document_order', function (Blueprint $table) {
            if (Schema::hasColumns('orders_document_order', ['orders_document_order_documents_id_foreign', 'documents_id'])) {
                $table->dropForeign('orders_document_order_documents_id_foreign');
                $table->dropColumn('documents_id');
            }
            if (!Schema::hasColumn('orders_document_order', 'document_order_id')) {
                $table->integer('document_order_id')->unsigned();
                $table->foreign('document_order_id')->references('id')->on('document_order');
            }
        });
    }

    public function down()
    {
        Schema::table('orders_document_order', function (Blueprint $table) {
            $table->dropForeign('orders_document_order_document_order_id_foreign');
            $table->dropColumn('document_order_id');
            $table->integer('documents_id')->unsigned();
            $table->foreign('documents_id')->references('id')->on('documents');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEntityToDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('documents', ['order_id', 'document_status_id', 'entity_id', 'entity'])) {
            Schema::table('documents', function (Blueprint $table) {
                $table->unsignedInteger('order_id')->nullable()->change();
                $table->unsignedInteger('document_status_id')->nullable()->change();
                $table->unsignedInteger('entity_id')->nullable();
                $table->string('entity')->nullable();
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
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('entity_id');
            $table->dropColumn('entity');
        });
    }
}

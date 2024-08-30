<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMapasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('mapas', ['lpco_document', 'lpco_document_signed', 'phyto_certificate', 'phyto_certificate_signed', 'nfe_document', 'due_document', 'date_currency_fee', 'observation', 'additional_declaration'])) {
            Schema::table('mapas', function (Blueprint $table) {
                $table->string('lpco_document')->nullable();
                $table->string('lpco_document_signed')->nullable();
                $table->string('phyto_certificate')->nullable();
                $table->string('phyto_certificate_signed')->nullable();
                $table->string('nfe_document')->nullable();
                $table->string('nfe_key')->nullable();
                $table->string('due_document')->nullable();
                $table->date('date_currency_fee')->nullable();
                $table->text('observation')->nullable();
                $table->text('additional_declaration')->nullable();
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
        Schema::table('mapas', function (Blueprint $table) {
            $table->dropColumn('lpco_document');
            $table->dropColumn('lpco_document_signed');
            $table->dropColumn('phyto_certificate');
            $table->dropColumn('phyto_certificate_signed');
            $table->dropColumn('nfe_document');
            $table->dropColumn('nfe_key');
            $table->dropColumn('due_document');
            $table->dropColumn('date_currency_fee');
            $table->dropColumn('observation');
            $table->dropColumn('additional_declaration');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHealthToDraftDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('draft_documents', function (Blueprint $table) {
            $table->string('health_certificate')->nullable();
            $table->integer('health_certificate_status')->defaut(1);
            $table->text('health_certificate_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('draft_documents', function (Blueprint $table) {
            $table->dropColumn('health_certificate');
            $table->dropColumn('health_certificate_status');
            $table->dropColumn('health_certificate_reason');
        });
    }
}

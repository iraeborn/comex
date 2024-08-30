<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNonGmoCertificateToDraftDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('draft_documents', function (Blueprint $table) {
            !Schema::hasColumn('draft_documents', 'non_gmo_certificate') ?? $table->string('non_gmo_certificate')->nullable();
            !Schema::hasColumn('draft_documents', 'non_gmo_certificate_status') ?? $table->integer('non_gmo_certificate_status')->nullable();
            !Schema::hasColumn('draft_documents', 'non_gmo_certificate_reason') ?? $table->text('non_gmo_certificate_reason')->nullable();
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
            $table->dropColumn('non_gmo_certificate');
            $table->dropColumn('non_gmo_certificate_status');
            $table->dropColumn('non_gmo_certificate_reason');
        });
    }
}

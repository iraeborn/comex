<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequiredColumnToDraftDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('draft_documents', function (Blueprint $table) {
            !Schema::hasColumn('draft_documents', 'required') ?? $table->text('required')->nullable();
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
            $table->dropColumn('required');
        });
    }
}

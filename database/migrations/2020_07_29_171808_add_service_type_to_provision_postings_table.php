<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceTypeToProvisionPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provision_postings', function (Blueprint $table) {
            !Schema::hasColumn('provision_postings', 'service_type') ?? $table->string('service_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provision_postings', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });
    }
}

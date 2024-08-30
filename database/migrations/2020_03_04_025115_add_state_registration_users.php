<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateRegistrationUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            !Schema::hasColumn('users', 'state_registration') ?? $table->string('state_registration')->nullable();
            !Schema::hasColumn('users', 'tax_substitution') ?? $table->string('tax_substitution')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('state_registration');
            $table->dropColumn('tax_substitution');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->renameColumn('iban', 'beneficiary_iban');
            $table->renameColumn('swift_code', 'beneficiary_swift');
            $table->renameColumn('branch_number', 'beneficiary_branch');
            $table->renameColumn('local', 'beneficiary_agency');
            $table->renameColumn('account_nr_one', 'beneficiary_account');
            $table->renameColumn('beneficiary_bank', 'beneficiary_company');

            $table->string('beneficiary_clearing', 255)->nullable();
            $table->string('beneficiary_chips', 255)->nullable();

            !Schema::hasColumn('banks', 'entity') ?? $table->string('entity')->nullable();
            !Schema::hasColumn('banks', 'entity_id') ?? $table->integer('entity_id');

            $table->renameColumn('intermediary_bank', 'intermediary_name');
            $table->renameColumn('swift_code_two', 'intermediary_swift');
            $table->renameColumn('account_nr_two', 'intermediary_account');

            $table->string('intermediary_iban', 255)->nullable();
            $table->string('intermediary_branch', 255)->nullable();
            $table->string('intermediary_agency', 255)->nullable();
            $table->string('intermediary_clearing', 255)->nullable();
            $table->string('intermediary_chips', 255)->nullable();
            $table->string('intermediary_company', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->renameColumn('beneficiary_iban', 'iban');
            $table->renameColumn('beneficiary_swift', 'swift_code');
            $table->renameColumn('beneficiary_branch', 'branch_number');
            $table->renameColumn('beneficiary_agency', 'local');
            $table->renameColumn('beneficiary_account', 'account_nr_one');
            $table->renameColumn('beneficiary_company', 'beneficiary_bank');

            $table->dropColumn('beneficiary_clearing');
            $table->dropColumn('beneficiary_chips');

            $table->renameColumn('intermediary_name', 'intermediary_bank');
            $table->renameColumn('intermediary_swift', 'swift_code_two');
            $table->renameColumn('intermediary_account', 'account_nr_two');

            $table->dropColumn('intermediary_iban');
            $table->dropColumn('intermediary_branch');
            $table->dropColumn('intermediary_agency');
            $table->dropColumn('intermediary_clearing');
            $table->dropColumn('intermediary_chips');
            $table->dropColumn('intermediary_company');
        });
    }
}

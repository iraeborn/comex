<?php

use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('transaction_types')->insert([
            'name' => 'Initial Balance',
            'display_to_importer' => true,
            'deletable' => false
        ]);
        \DB::table('transaction_types')->insert([
            'name' => 'Advance',
            'display_to_importer' => true,
            'deletable' => true
        ]);
        \DB::table('transaction_types')->insert([
            'name' => 'Exchange',
            'display_to_importer' => false,
            'deletable' => true
        ]);
        \DB::table('transaction_types')->insert([
            'name' => 'Banker\'s Comission',
            'display_to_importer' => false,
            'deletable' => true
        ]);
        \DB::table('transaction_types')->insert([
            'name' => 'Balance',
            'display_to_importer' => true,
            'deletable' => true
        ]);
        \DB::table('transaction_types')->insert([
            'name' => 'Supplier Payment',
            'display_to_importer' => true,
            'deletable' => true
        ]);
        \DB::table('transaction_types')->insert([
            'name' => 'Foreign Account',
            'display_to_importer' => false,
            'deletable' => true
        ]);
    }
}

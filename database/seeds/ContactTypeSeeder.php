<?php

use Illuminate\Database\Seeder;

class ContactTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('contact_type')->insert([
            'id' => 1,
            'name' => 'E-mail comercial',
        ]);

        \DB::table('contact_type')->insert([
            'id' => 2,
            'name' => 'E-mail document',
        ]);

        \DB::table('contact_type')->insert([
            'id' => 3,
            'name' => 'Phone comercial',
        ]);

        \DB::table('contact_type')->insert([
            'id' => 4,
            'name' => 'Phone document',
        ]);
    }
}

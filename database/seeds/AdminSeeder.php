<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        \DB::table('users')->insert([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'a@a.com',
            'username' => 'admin',
            'password' => bcrypt('admin123$'),
            'country' => 'Brazil'
        ]);

        \DB::table('users_roles')->insert([
            'user_id' => 1,
            'role_id' => 1,
        ]);
    }
}

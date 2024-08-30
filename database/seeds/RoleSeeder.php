<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private static $roles = array();

    public function __construct()
    {
        self::$roles[] = [
            'id' => 1, 'role' => 'Admin'
        ];
        self::$roles[] = [
            'id' => 2, 'role' => 'Client'
        ];
        self::$roles[] = [
            'role' => 'Fumigation Agency'
        ];
        self::$roles[] = [
            'role' => 'Insurance Agency'
        ];
        self::$roles[] = [
            'role' => 'Forwarding Agent'
        ];

        self::$roles[] = [
            'role' => 'Terminal Agent'
        ];
        self::$roles[] = [
            'role' => 'Railway Agent'
        ];
        self::$roles[] = [
            'role' => 'Carrier'
        ];
        self::$roles[] = [
            'role' => 'Broker'
        ];
        // self::$roles[] = [
        //     'role' => 'Master'
        // ];
    }

    public function run()
    {   

        foreach (self::$roles as  $role) {
            if(!\DB::table('roles')->where('name', $role['role'])->first()){
                \DB::table('roles')->insert(['name' => $role['role']]);
            }
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private static $groups = array();

    public function __construct()
    {
        self::$groups[] = [
            'id' => 1, 'name' => 'Popcorn'
        ];

        self::$groups[] = [
            'id' => 2, 'name' => 'Default'
        ];
    }

    public function run()
    {   

        foreach (self::$groups as  $group) {
            if(!\DB::table('groups')->where('name', $group['name'])->first()){
                \DB::table('groups')->insert(['name' => $group['name'], 'id' => $group['id']]);
            }


            if($group['id'] == 2){
                \DB::table('orders')
                ->whereNull('group_id')
                ->update(['group_id' => $group['id']]);

                \DB::table('users')
                ->whereNull('group_id')
                ->update(['group_id' => $group['id']]);
            }
           
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('order_status')->insert(['name' => 'Waiting for documents']);
        \DB::table('order_status')->insert(['name' => 'Waiting for document approval']);
        \DB::table('order_status')->insert(['name' => 'Waiting for shipping approval']);
        \DB::table('order_status')->insert(['name' => 'Finished']);
    }
}

<?php

use Illuminate\Database\Seeder;

class ShippingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('shipping_status')->insert(['name' => 'Waiting']);
        \DB::table('shipping_status')->insert(['name' => 'Accepted']);
        \DB::table('shipping_status')->insert(['name' => 'Rejected']);
    }
}

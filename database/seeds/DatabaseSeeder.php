<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ContactTypeSeeder::class);
        $this->call(DocumentSeeder::class);
        $this->call(OriginalDocumentSeeder::class);
        $this->call(PortSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(ShippingStatusSeeder::class);
        $this->call(TransactionTypeSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(OrderStatusSeeder::class);
        $this->call(GroupSeeder::class);
    }
}

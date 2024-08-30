<?php

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private static $groups = array();

    public function __construct()
    {
    }

    public function run()
    {   
        if(!\DB::table('services')->first()){
            \DB::statement("insert into services (name) select distinct service_type from provider_contracts;");
            \DB::statement("update provider_contracts set service_id = (
                select max(services.id) from services where services.name = provider_contracts.service_type
            ) where service_id is null;");
        }
        
    }
}

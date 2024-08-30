<?php

use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->status();
        $this->types();
    }

    private function status () {        
        \DB::table('document_status')->insert(['name' => 'In Analysis']);
        \DB::table('document_status')->insert(['name' => 'Rejected']);
        \DB::table('document_status')->insert(['name' => 'Aproved']);     
    }

    private function types () {
        \DB::table('document_type')->insert(['name' => 'Contract']);
        \DB::table('document_type')->insert(['name' => 'Contract Signed']);
        \DB::table('document_type')->insert(['name' => 'Proforma Invoice']);
        \DB::table('document_type')->insert(['name' => 'Proforma Invoice Signed']);
        \DB::table('document_type')->insert(['name' => 'Swift Advance']);
        \DB::table('document_type')->insert(['name' => 'Instruções  Documentos']);
        \DB::table('document_type')->insert(['name' => 'Label Model']);    
    }
}

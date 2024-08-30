<?php

use Illuminate\Database\Seeder;

class OriginalDocumentSeeder extends Seeder
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
        \DB::table('original_documents_status')->insert(['name' => 'In Analysis']);
        \DB::table('original_documents_status')->insert(['name' => 'Accepted']);
        \DB::table('original_documents_status')->insert(['name' => 'Rejected']);        
    }

    private function types () {
        \DB::table('original_documents_type')->insert(['name' => 'DRAFT BL']);
        \DB::table('original_documents_type')->insert(['name' => 'DRAFT COMERCIAL']);
        \DB::table('original_documents_type')->insert(['name' => 'PACKING LIST']);
        \DB::table('original_documents_type')->insert(['name' => 'CERTIFICATE OF ORIGIN']);
        \DB::table('original_documents_type')->insert(['name' => 'CERTIFICATE OF FUMIGATION']);
        \DB::table('original_documents_type')->insert(['name' => 'CERTIFICATE OF QUALITY']);
        \DB::table('original_documents_type')->insert(['name' => 'CERTIFICATE OF WEIGHT']);
        \DB::table('original_documents_type')->insert(['name' => 'CERTIFICATE OF SEGURO']);
        \DB::table('original_documents_type')->insert(['name' => 'CERTIFICATE OF PHYTO']);
        \DB::table('original_documents_type')->insert(['name' => 'REPORT']);
        \DB::table('original_documents_type')->insert(['name' => 'OTHER DOCUMENTS']);
        \DB::table('original_documents_type')->insert(['name' => 'HEALTH CERTIFICATE']);
        \DB::table('original_documents_type')->insert(['name' => 'NON-GMO CERTIFICATE']);        
    }
}

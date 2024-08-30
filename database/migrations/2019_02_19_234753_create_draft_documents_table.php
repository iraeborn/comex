<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDraftDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->softDeletes();

            $table->unsignedInteger('order_id');
            
            $table->string('draft_bl')->nullable();
            $table->string('draft_comercial')->nullable();
            $table->string('packing_list')->nullable();
            $table->string('certificate_origin')->nullable();
            $table->string('certificate_fumigation')->nullable();
            $table->string('certificate_quality')->nullable();
            $table->string('certificate_weight')->nullable();
            $table->string('certificate_seguro')->nullable();
            $table->string('certificate_phyto')->nullable();
            $table->string('report')->nullable();
            $table->text('others')->nullable();
            
            $table->integer('draft_bl_status')->defaut(1);
            $table->integer('draft_comercial_status')->defaut(1);
            $table->integer('packing_list_status')->defaut(1);
            $table->integer('certificate_origin_status')->defaut(1);
            $table->integer('certificate_fumigation_status')->defaut(1);
            $table->integer('certificate_quality_status')->defaut(1);
            $table->integer('certificate_weight_status')->defaut(1);
            $table->integer('certificate_seguro_status')->defaut(1);
            $table->integer('certificate_phyto_status')->defaut(1);
            $table->integer('report_status')->defaut(1);
            $table->text('others_status')->nullable();
            
            $table->text('draft_bl_reason')->nullable();
            $table->text('draft_comercial_reason')->nullable();
            $table->text('packing_list_reason')->nullable();
            $table->text('certificate_origin_reason')->nullable();
            $table->text('certificate_fumigation_reason')->nullable();
            $table->text('certificate_quality_reason')->nullable(); 
            $table->text('certificate_weight_reason')->nullable();
            $table->text('certificate_seguro_reason')->nullable();
            $table->text('certificate_phyto_reason')->nullable();
            $table->text('report_reason')->nullable();
            $table->text('others_reason')->nullable();
            
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('draft_documents');
    }
}

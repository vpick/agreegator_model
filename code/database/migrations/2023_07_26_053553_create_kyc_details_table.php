<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('kyc_type'); 
            $table->string('shipment_type');
            $table->string('iec_code');
            $table->string('iec_branch_code');
            $table->longText('iec_photo');
            $table->string('document_type1');  
            $table->string('document_id1'); 
            $table->string('name_on_doc1'); 
            $table->longText('doc_photo1'); 
            $table->string('document_type2');  
            $table->string('document_id2'); 
            $table->string('name_on_doc2'); 
            $table->longText('doc_photo2'); 
            $table->longText('gst_certificate');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kyc_details');
    }
}
